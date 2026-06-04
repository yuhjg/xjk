<?php
namespace app\admin\controller;

use think\Controller;
use think\facade\Session;
use app\common\model\Admin;

class Login extends Controller
{
    public function index()
    {
        if (Session::has('admin_id')) {
            $this->redirect('admin/index/index');
        }
        return $this->fetch();
    }

    public function doLogin()
    {
        $username = $this->request->param('username', '', 'trim');
        $password = $this->request->param('password', '', 'trim');
        $captcha = $this->request->param('captcha', '', 'trim');

        if (empty($username) || empty($password)) {
            return json(['code' => 0, 'msg' => '用户名或密码不能为空']);
        }

        $admin = Admin::where('username', $username)->find();
        if (!$admin) {
            return json(['code' => 0, 'msg' => '用户不存在']);
        }

        if (md5($password) != $admin->password) {
            return json(['code' => 0, 'msg' => '密码错误']);
        }

        // 更新登录信息
        $admin->last_login_time = time();
        $admin->last_login_ip = $this->request->ip();
        $admin->save();

        Session::set('admin_id', $admin->id);
        Session::set('admin_name', $admin->username);

        return json(['code' => 1, 'msg' => '登录成功']);
    }

    public function logout()
    {
        Session::clear();
        $this->redirect('admin/login/index');
    }
}
