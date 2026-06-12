<?php
namespace app\admin\controller;

use app\common\model\Product as ProductModel;
use app\common\model\ProductCategory;

class Product extends Base
{
    public function index()
    {
        $keyword = $this->request->param('keyword', '', 'trim');
        $category_id = $this->request->param('category_id', 0, 'intval');

        $query = ProductModel::with('category');
        if ($keyword) {
            $query->where('title', 'like', '%' . $keyword . '%');
        }
        if ($category_id > 0) {
            // 支持按一级分类筛选（包含其子分类的产品）
            $categoryIds = ProductCategory::getCategoryIdsWithChildren($category_id);
            $query->whereIn('category_id', $categoryIds);
        }

        $products = $query->order('sort', 'asc')->order('id', 'desc')->paginate(15, false, [
            'query' => ['keyword' => $keyword, 'category_id' => $category_id]
        ]);

        $categories = ProductCategory::where('status', 1)->order('sort', 'asc')->order('parent_id', 'asc')->select();

        $this->assign('products', $products);
        $this->assign('categories', $categories);
        $this->assign('keyword', $keyword);
        $this->assign('category_id', $category_id);
        return $this->fetch();
    }

    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $result = $this->validate($data, [
                'title' => 'require|max:200',
                'category_id' => 'require|number',
            ], [
                'title.require' => '产品名称不能为空',
                'category_id.require' => '请选择分类',
            ]);

            if (true !== $result) {
                return $this->iframeMsg($result, 0);
            }

            $image = $this->uploadFile('image', 'product');
            if ($image) {
                $data['image'] = $image;
            }

            // 处理多图上传（最多5张）
            $images = $this->uploadMultiImages('images', 'product', 5);
            if (!empty($images)) {
                $data['images'] = json_encode($images, JSON_UNESCAPED_SLASHES);
            }

            if (ProductModel::create($data)) {
                return $this->iframeMsg('添加成功', 1, '/admin/product');
            }
            return $this->iframeMsg('添加失败', 0);
        }

        $categoryTree = ProductCategory::getCategoryTree();
        $this->assign('categoryTree', $categoryTree);
        return $this->fetch();
    }

    public function edit()
    {
        $id = $this->request->param('id', 0, 'intval');

        if ($this->request->isPost()) {
            $data = $this->request->post();
            $result = $this->validate($data, [
                'title' => 'require|max:200',
                'category_id' => 'require|number',
            ], [
                'title.require' => '产品名称不能为空',
                'category_id.require' => '请选择分类',
            ]);

            if (true !== $result) {
                return $this->iframeMsg($result, 0);
            }

            $image = $this->uploadFile('image', 'product');
            if ($image) {
                $data['image'] = $image;
            } else {
                unset($data['image']);
            }

            // 处理多图上传
            // 1. 获取已有的多图
            $product = ProductModel::get($id);
            $existImages = [];
            if ($product && $product->images) {
                $decoded = json_decode($product->images, true);
                if (is_array($decoded)) {
                    $existImages = $decoded;
                }
            }

            // 2. 获取表单中标记删除的图片索引
            $removeIndexes = $this->request->post('remove_images', '');
            if ($removeIndexes) {
                $removeArr = explode(',', $removeIndexes);
                foreach ($removeArr as $idx) {
                    $idx = intval($idx);
                    if (isset($existImages[$idx])) {
                        unset($existImages[$idx]);
                    }
                }
                // 重新索引
                $existImages = array_values($existImages);
            }

            // 3. 上传新的多图
            $newImages = $this->uploadMultiImages('images', 'product', 5);
            if (!empty($newImages)) {
                $existImages = array_merge($existImages, $newImages);
            }

            // 4. 最多保留5张
            $existImages = array_slice($existImages, 0, 5);

            // 5. 如果主图没设，取多图第一张
            if (empty($data['image']) && !empty($existImages) && empty($product->image)) {
                $data['image'] = $existImages[0];
            }

            $data['images'] = json_encode($existImages, JSON_UNESCAPED_SLASHES);
            unset($data['remove_images']);

            if (ProductModel::where('id', $id)->update($data) !== false) {
                return $this->iframeMsg('修改成功', 1, '/admin/product');
            }
            return $this->iframeMsg('修改失败', 0);
        }

        $product = ProductModel::get($id);
        if (!$product) {
            $this->error('产品不存在');
        }

        $categoryTree = ProductCategory::getCategoryTree();
        $this->assign('product', $product);
        $this->assign('categoryTree', $categoryTree);
        return $this->fetch();
    }

    public function delete()
    {
        $id = $this->request->param('id', 0, 'intval');
        if (ProductModel::destroy($id)) {
            return $this->successJson('删除成功');
        }
        return $this->errorJson('删除失败');
    }

    public function setStatus()
    {
        $id = $this->request->param('id', 0, 'intval');
        $status = $this->request->param('status', 0, 'intval');

        if (ProductModel::where('id', $id)->update(['status' => $status]) !== false) {
            return $this->successJson('操作成功');
        }
        return $this->errorJson('操作失败');
    }

    /**
     * 批量上传图片（最多$max张）
     * @param string $fieldName 表单字段名
     * @param string $subDir 子目录
     * @param int $max 最大数量
     * @return array 上传成功的图片路径数组
     */
    protected function uploadMultiImages($fieldName, $subDir = 'product', $max = 5)
    {
        $images = [];
        if (empty($_FILES[$fieldName])) {
            return $images;
        }

        $files = $_FILES[$fieldName];
        $count = is_array($files['name']) ? count($files['name']) : 0;

        for ($i = 0; $i < $count && $i < $max; $i++) {
            if ($files['error'][$i] !== 0) {
                continue;
            }

            $tmpName = $files['tmp_name'][$i];
            $originName = $files['name'][$i];

            if (!is_uploaded_file($tmpName)) {
                continue;
            }

            $ext = strtolower(pathinfo($originName, PATHINFO_EXTENSION));
            $allowExt = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];
            if (!in_array($ext, $allowExt)) {
                $ext = 'jpg';
            }

            $dateDir = date('Ymd');
            $saveDir = './uploads/' . $subDir . '/' . $dateDir;
            if (!is_dir($saveDir)) {
                mkdir($saveDir, 0777, true);
            }

            $fileName = md5(microtime(true) . mt_rand(1000, 9999) . $i) . '.' . $ext;
            $destPath = $saveDir . '/' . $fileName;

            if (move_uploaded_file($tmpName, $destPath)) {
                $images[] = '/uploads/' . $subDir . '/' . $dateDir . '/' . $fileName;
            }
        }

        return $images;
    }
}
