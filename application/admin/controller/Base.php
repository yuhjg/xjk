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
     * iframe内表单提交后返回提示页面
     * @param string $msg 提示信息
     * @param int $code 1成功 0失败
     * @param string $jump 成功后跳转URL
     */
    protected function iframeMsg($msg, $code = 1, $jump = '')
    {
        $color = $code ? '#28a745' : '#dc3545';
        $html = '<!DOCTYPE html><html><head><meta charset="utf-8">';
        $html .= '<link rel="stylesheet" href="/static/css/admin.css"></head><body>';
        $html .= '<div style="text-align:center;padding:60px;">';
        $html .= '<p style="font-size:18px;color:' . $color . ';margin-bottom:20px;">' . $msg . '</p>';
        if ($jump) {
            $html .= '<p style="color:#999;">3秒后自动跳转...</p>';
            $html .= '<script>setTimeout(function(){window.location.href="' . $jump . '";}, 1500);</script>';
        } else {
            $html .= '<a href="javascript:history.back()" style="color:#1a5ca1;">返回上一页</a>';
        }
        $html .= '</div></body></html>';
        return response($html);
    }

    /**
     * 文件上传 - 兼容Windows/PHPStudy环境
     */
    protected function uploadFile($fieldName, $subDir = 'common')
    {
        // 检查是否有文件上传
        if (empty($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== 0) {
            // 尝试TP方式
            try {
                $file = $this->request->file($fieldName);
                if (!$file) {
                    return false;
                }
                $info = $file->move('./uploads/' . $subDir);
                if ($info) {
                    $path = '/uploads/' . $subDir . '/' . str_replace('\\', '/', $info->getSaveName());
                    return $path;
                }
            } catch (\Exception $e) {
                return false;
            }
            return false;
        }

        // 原生方式上传
        $tmpName = $_FILES[$fieldName]['tmp_name'];
        $originName = $_FILES[$fieldName]['name'];

        if (!is_uploaded_file($tmpName)) {
            return false;
        }

        // 获取扩展名
        $ext = strtolower(pathinfo($originName, PATHINFO_EXTENSION));

        // 允许的图片类型
        $allowExt = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];
        if (!in_array($ext, $allowExt)) {
            $ext = 'jpg'; // 默认后缀
        }

        // 生成保存路径
        $dateDir = date('Ymd');
        $saveDir = './uploads/' . $subDir . '/' . $dateDir;
        if (!is_dir($saveDir)) {
            mkdir($saveDir, 0777, true);
        }

        $fileName = md5(microtime(true) . mt_rand(1000, 9999)) . '.' . $ext;
        $destPath = $saveDir . '/' . $fileName;

        if (move_uploaded_file($tmpName, $destPath)) {
            return '/uploads/' . $subDir . '/' . $dateDir . '/' . $fileName;
        }

        return false;
    }
}
