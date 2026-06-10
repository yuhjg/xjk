<?php
namespace app\admin\controller;

use app\common\model\Company as CompanyModel;

class Company extends Base
{
    public function index()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            // 文件上传处理
            $fileFields = ['logo', 'wechat_qrcode', 'company_image',
                'stat1_image', 'stat2_image', 'stat3_image', 'stat4_image'];
            foreach ($fileFields as $field) {
                $file = $this->uploadFile($field, 'company');
                if ($file) {
                    $data[$field] = $file;
                } else {
                    unset($data[$field]);
                }
            }

            $company = CompanyModel::find(1);
            if ($company) {
                if ($company->save($data) !== false) {
                    return $this->iframeMsg('保存成功', 1, '/admin/company');
                }
            } else {
                if (CompanyModel::create($data)) {
                    return $this->iframeMsg('保存成功', 1, '/admin/company');
                }
            }
            return $this->iframeMsg('保存失败', 0);
        }

        $company = CompanyModel::find(1);
        $this->assign('company', $company);
        return $this->fetch();
    }
}
