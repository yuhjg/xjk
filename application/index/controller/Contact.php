<?php
namespace app\index\controller;

use app\common\model\Company;

class Contact extends Base
{
    public function index()
    {
        $company = Company::find(1);
        $this->assign('company', $company);
        return $this->fetch();
    }
}
