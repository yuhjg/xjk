<?php
namespace app\admin\controller;

use app\common\model\Image as ImageModel;

class Image extends Base
{
    public function index()
    {
        $images = ImageModel::order('sort', 'asc')->order('id', 'desc')->paginate(20);
        $this->assign('images', $images);
        return $this->fetch();
    }

    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $image = $this->uploadFile('image', 'images');
            if ($image) {
                $data['image'] = $image;
            }

            if (ImageModel::create($data)) {
                return $this->iframeMsg('添加成功', 1, '/admin/image');
            }
            return $this->iframeMsg('添加失败', 0);
        }
        return $this->fetch();
    }

    public function edit()
    {
        $id = $this->request->param('id', 0, 'intval');

        if ($this->request->isPost()) {
            $data = $this->request->post();

            $image = $this->uploadFile('image', 'images');
            if ($image) {
                $data['image'] = $image;
            } else {
                unset($data['image']);
            }

            if (ImageModel::where('id', $id)->update($data) !== false) {
                return $this->iframeMsg('修改成功', 1, '/admin/image');
            }
            return $this->iframeMsg('修改失败', 0);
        }

        $image = ImageModel::get($id);
        $this->assign('image', $image);
        return $this->fetch();
    }

    public function delete()
    {
        $id = $this->request->param('id', 0, 'intval');
        if (ImageModel::destroy($id)) {
            return $this->successJson('删除成功');
        }
        return $this->errorJson('删除失败');
    }
}
