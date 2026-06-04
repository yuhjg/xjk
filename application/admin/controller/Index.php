<?php
namespace app\admin\controller;

use think\facade\Session;

class Index extends Base
{
    public function index()
    {
        $this->assign('admin_name', Session::get('admin_name'));
        return $this->fetch();
    }

    public function welcome()
    {
        return $this->fetch();
    }
}
