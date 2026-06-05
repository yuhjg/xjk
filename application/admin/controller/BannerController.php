<?php
namespace app\admin\controller;

use app\common\model\Banner;

class BannerController extends Base
{
    public function index()
    {
        $banners = Banner::order('sort', 'asc')->paginate(20);
        $this->assign('banners', $banners);
        return $this->fetch();
    }

    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $image = $this->uploadFile('image', 'banner');
            if ($image) {
                $data['image'] = $image;
            }

            if (Banner::create($data)) {
                return $this->iframeMsg('添加成功', 1, '/admin/banner');
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

            $image = $this->uploadFile('image', 'banner');
            if ($image) {
                $data['image'] = $image;
            } else {
                unset($data['image']);
            }

            if (Banner::where('id', $id)->update($data) !== false) {
                return $this->iframeMsg('修改成功', 1, '/admin/banner');
            }
            return $this->iframeMsg('修改失败', 0);
        }

        $banner = Banner::get($id);
        $this->assign('banner', $banner);
        return $this->fetch();
    }

    public function delete()
    {
        $id = $this->request->param('id', 0, 'intval');
        if (Banner::destroy($id)) {
            return $this->successJson('删除成功');
        }
        return $this->errorJson('删除失败');
    }
}
