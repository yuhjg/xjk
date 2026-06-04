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
            // 兼容FormData提交（无X-Requested-With头）
            if ($this->request->isAjax() || $this->request->header('X-Requested-With') || $this->request->isPost()) {
                // POST请求可能来自iframe表单，不重定向，让具体方法返回JSON
                return;
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
     * move()路径相对于入口文件(public/)，所以用./uploads即可
     */
    protected function uploadFile($fieldName, $subDir = 'common')
    {
        try {
            $file = $this->request->file($fieldName);
            if (!$file) {
                return false;
            }
            $info = $file->move('./uploads/' . $subDir);
            if ($info) {
                return '/uploads/' . $subDir . '/' . str_replace('\\', '/', $info->getSaveName());
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}
