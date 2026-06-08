<?php
namespace app\admin\controller;

use app\common\model\ProductCategory as ProductCategoryModel;

class ProductCategory extends Base
{
    public function index()
    {
        // 获取所有分类，按树形展示
        $topCategories = ProductCategoryModel::where('parent_id', 0)->order('sort', 'asc')->order('id', 'asc')->select();
        foreach ($topCategories as $cat) {
            $cat->children_list = ProductCategoryModel::where('parent_id', $cat->id)->order('sort', 'asc')->order('id', 'asc')->select();
        }
        $this->assign('topCategories', $topCategories);
        return $this->fetch();
    }

    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $result = $this->validate($data, [
                'name' => 'require|max:50',
            ], [
                'name.require' => '分类名称不能为空',
            ]);

            if (true !== $result) {
                return $this->errorJson($result);
            }

            // 如果选了父分类，设置parent_id；否则为0（一级分类）
            $data['parent_id'] = isset($data['parent_id']) ? intval($data['parent_id']) : 0;

            if (ProductCategoryModel::create($data)) {
                return $this->successJson('添加成功');
            }
            return $this->errorJson('添加失败');
        }

        // 获取一级分类供选择父分类
        $topCategories = ProductCategoryModel::where('status', 1)->where('parent_id', 0)->order('sort', 'asc')->select();
        $this->assign('topCategories', $topCategories);
        return $this->fetch();
    }

    public function edit()
    {
        $id = $this->request->param('id', 0, 'intval');

        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['parent_id'] = isset($data['parent_id']) ? intval($data['parent_id']) : 0;

            // 不能把自己设为自己的子分类
            if ($data['parent_id'] == $id) {
                return $this->errorJson('不能将自身设为父分类');
            }

            if (ProductCategoryModel::where('id', $id)->update($data) !== false) {
                return $this->successJson('修改成功');
            }
            return $this->errorJson('修改失败');
        }

        $category = ProductCategoryModel::get($id);
        $topCategories = ProductCategoryModel::where('status', 1)->where('parent_id', 0)->where('id', '<>', $id)->order('sort', 'asc')->select();
        $this->assign('category', $category);
        $this->assign('topCategories', $topCategories);
        return $this->fetch();
    }

    public function delete()
    {
        $id = $this->request->param('id', 0, 'intval');

        // 检查是否有子分类
        $childCount = ProductCategoryModel::where('parent_id', $id)->count();
        if ($childCount > 0) {
            return $this->errorJson('该分类下有子分类，请先删除子分类');
        }

        if (ProductCategoryModel::destroy($id)) {
            return $this->successJson('删除成功');
        }
        return $this->errorJson('删除失败');
    }
}
