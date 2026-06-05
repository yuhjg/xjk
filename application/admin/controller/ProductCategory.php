<?php
namespace app\admin\controller;

use app\common\model\ProductCategory as ProductCategoryModel;

class ProductCategory extends Base
{
    public function index()
    {
        $categories = ProductCategoryModel::order('sort', 'asc')->paginate(20);
        $this->assign('categories', $categories);
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

            if (ProductCategoryModel::create($data)) {
                return $this->successJson('添加成功');
            }
            return $this->errorJson('添加失败');
        }
        return $this->fetch();
    }

    public function edit()
    {
        $id = $this->request->param('id', 0, 'intval');

        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (ProductCategoryModel::where('id', $id)->update($data) !== false) {
                return $this->successJson('修改成功');
            }
            return $this->errorJson('修改失败');
        }

        $category = ProductCategoryModel::get($id);
        $this->assign('category', $category);
        return $this->fetch();
    }

    public function delete()
    {
        $id = $this->request->param('id', 0, 'intval');
        if (ProductCategoryModel::destroy($id)) {
            return $this->successJson('删除成功');
        }
        return $this->errorJson('删除失败');
    }
}
