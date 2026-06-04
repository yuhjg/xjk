<?php
namespace app\admin\controller;

use think\Controller;
use think\facade\Session;

class Base extends Controller
{
    protected function initialize()
    {
        parent::initialize();
        if (!Session::has('admin_id')) {
            if ($this->request->isAjax()) {
                return json(['code' => 0, 'msg' => '请先登录']);
            }
            $this->redirect('admin/login/index');
        }
    }

    protected function successJson($msg = '操作成功', $data = [])
    {
        return json(['code' => 1, 'msg' => $msg, 'data' => $data]);
    }

    protected function errorJson($msg = '操作失败')
    {
        return json(['code' => 0, 'msg' => $msg]);
    }

    /**
     * 文件上传到public/uploads目录
     */
    protected function uploadFile($fieldName, $subDir = 'common')
    {
        $file = $this->request->file($fieldName);
        if (!$file) {
            return false;
        }
        $info = $file->move('./uploads/' . $subDir);
        if ($info) {
            return '/uploads/' . $subDir . '/' . str_replace('\\', '/', $info->getSaveName());
        }
        return false;
    }
}
