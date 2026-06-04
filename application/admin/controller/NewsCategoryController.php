<?php
namespace app\admin\controller;

use app\common\model\NewsCategory;

class NewsCategoryController extends Base
{
    public function index()
    {
        $categories = NewsCategory::order('sort', 'asc')->paginate(20);
        $this->assign('categories', $categories);
        return $this->fetch();
    }

    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (NewsCategory::create($data)) {
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
            if (NewsCategory::where('id', $id)->update($data) !== false) {
                return $this->successJson('修改成功');
            }
            return $this->errorJson('修改失败');
        }

        $category = NewsCategory::get($id);
        $this->assign('category', $category);
        return $this->fetch();
    }

    public function delete()
    {
        $id = $this->request->param('id', 0, 'intval');
        if (NewsCategory::destroy($id)) {
            return $this->successJson('删除成功');
        }
        return $this->errorJson('删除失败');
    }
}
