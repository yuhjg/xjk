<?php
namespace app\admin\controller;

use app\common\model\Product;
use app\common\model\ProductCategory;

class ProductController extends Base
{
    public function index()
    {
        $keyword = $this->request->param('keyword', '', 'trim');
        $category_id = $this->request->param('category_id', 0, 'intval');

        $query = Product::with('category');
        if ($keyword) {
            $query->where('title', 'like', '%' . $keyword . '%');
        }
        if ($category_id > 0) {
            $query->where('category_id', $category_id);
        }

        $products = $query->order('sort', 'asc')->order('id', 'desc')->paginate(15, false, [
            'query' => ['keyword' => $keyword, 'category_id' => $category_id]
        ]);

        $categories = ProductCategory::where('status', 1)->order('sort', 'asc')->select();

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
                return $this->errorJson($result);
            }

            $image = $this->uploadFile('image', 'product');
            if ($image) {
                $data['image'] = $image;
            }

            if (Product::create($data)) {
                return $this->successJson('添加成功');
            }
            return $this->errorJson('添加失败');
        }

        $categories = ProductCategory::where('status', 1)->order('sort', 'asc')->select();
        $this->assign('categories', $categories);
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
                return $this->errorJson($result);
            }

            $image = $this->uploadFile('image', 'product');
            if ($image) {
                $data['image'] = $image;
            } else {
                unset($data['image']);
            }

            if (Product::where('id', $id)->update($data) !== false) {
                return $this->successJson('修改成功');
            }
            return $this->errorJson('修改失败');
        }

        $product = Product::get($id);
        if (!$product) {
            $this->error('产品不存在');
        }

        $categories = ProductCategory::where('status', 1)->order('sort', 'asc')->select();
        $this->assign('product', $product);
        $this->assign('categories', $categories);
        return $this->fetch();
    }

    public function delete()
    {
        $id = $this->request->param('id', 0, 'intval');
        if (Product::destroy($id)) {
            return $this->successJson('删除成功');
        }
        return $this->errorJson('删除失败');
    }

    public function setStatus()
    {
        $id = $this->request->param('id', 0, 'intval');
        $status = $this->request->param('status', 0, 'intval');

        if (Product::where('id', $id)->update(['status' => $status]) !== false) {
            return $this->successJson('操作成功');
        }
        return $this->errorJson('操作失败');
    }
}
