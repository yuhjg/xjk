<?php
namespace app\admin\controller;

use app\common\model\ProductCategory;

class ProductCategoryController extends Base
{
    public function index()
    {
        $categories = ProductCategory::order('sort', 'asc')->paginate(20);
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

            if (ProductCategory::create($data)) {
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
            if (ProductCategory::where('id', $id)->update($data) !== false) {
                return $this->successJson('修改成功');
            }
            return $this->errorJson('修改失败');
        }

        $category = ProductCategory::get($id);
        $this->assign('category', $category);
        return $this->fetch();
    }

    public function delete()
    {
        $id = $this->request->param('id', 0, 'intval');
        if (ProductCategory::destroy($id)) {
            return $this->successJson('删除成功');
        }
        return $this->errorJson('删除失败');
    }
}
