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
            if ($this->request->isAjax() || $this->request->header('X-Requested-With') || $this->request->isPost()) {
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
     */
    protected function uploadFile($fieldName, $subDir = 'common')
    {
        // 优先使用$_FILES直接获取，避免TP的request->file()兼容性问题
        if (!empty($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] === 0) {
            $tmpName = $_FILES[$fieldName]['tmp_name'];
            $fileName = $_FILES[$fieldName]['name'];

            // 生成安全的文件名
            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
            $saveName = date('Ymd') . '/' . md5(microtime(true) . $fileName) . '.' . $ext;
            $saveDir = './uploads/' . $subDir . '/' . date('Ymd');

            if (!is_dir($saveDir)) {
                mkdir($saveDir, 0777, true);
            }

            $destPath = './uploads/' . $subDir . '/' . $saveName;
            if (move_uploaded_file($tmpName, $destPath)) {
                return '/uploads/' . $subDir . '/' . $saveName;
            }
            return false;
        }

        // 回退到TP的方式
        try {
            $file = $this->request->file($fieldName);
            if (!$file) {
                return false;
            }
            $info = $file->move('./uploads/' . $subDir);
            if ($info) {
                return '/uploads/' . $subDir . '/' . str_replace('\\', '/', $info->getSaveName());
            }
        } catch (\Exception $e) {
            return false;
        }
        return false;
    }
}
