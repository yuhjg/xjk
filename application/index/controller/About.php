<?php
namespace app\index\controller;

use app\common\model\Company;

class About extends Base
{
    public function index()
    {
        $company = Company::find(1);
        $this->assign('company', $company);
        return $this->fetch();
    }
    public function edit(){
            $company = Company::find(1);
            if ($company) {
            	$data['stat1_image'] = '';
                if ($company->save($data) !== false) {
                    echo 'save ok';
                }
            }
    }
}
