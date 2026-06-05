<?php
namespace app\admin\controller;

use app\common\model\Company;

class CompanyController extends Base
{
    public function index()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $logo = $this->uploadFile('logo', 'company');
            if ($logo) {
                $data['logo'] = $logo;
            } else {
                unset($data['logo']);
            }

            $wechat = $this->uploadFile('wechat_qrcode', 'company');
            if ($wechat) {
                $data['wechat_qrcode'] = $wechat;
            } else {
                unset($data['wechat_qrcode']);
            }

            $company = Company::find(1);
            if ($company) {
                if ($company->save($data) !== false) {
                    return $this->iframeMsg('保存成功', 1, '/admin/company');
                }
            } else {
                if (Company::create($data)) {
                    return $this->iframeMsg('保存成功', 1, '/admin/company');
                }
            }
            return $this->iframeMsg('保存失败', 0);
        }

        $company = Company::find(1);
        $this->assign('company', $company);
        return $this->fetch();
    }
}
