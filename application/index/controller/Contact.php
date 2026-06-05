<?php
namespace app\index\controller;

use app\common\model\Company;
use app\common\model\Message;

class Contact extends Base
{
    public function index()
    {
        $company = Company::find(1);
        $this->assign('company', $company);
        return $this->fetch();
    }

    public function submit()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            $result = $this->validate($data, [
                'name'    => 'require|max:50',
                'phone'   => 'require|max:30',
                'message' => 'require|max:1000',
            ], [
                'name.require'    => '请输入您的姓名',
                'phone.require'   => '请输入联系电话',
                'message.require' => '请输入留言内容',
            ]);

            if (true !== $result) {
                return json(['code' => 0, 'msg' => $result]);
            }

            $saveData = [
                'name'    => $data['name'],
                'phone'   => $data['phone'],
                'email'   => isset($data['email']) ? $data['email'] : '',
                'content' => $data['message'],
            ];

            if (Message::create($saveData)) {
                return json(['code' => 1, 'msg' => '留言提交成功，我们会尽快与您联系！']);
            }
            return json(['code' => 0, 'msg' => '留言提交失败，请稍后重试']);
        }
        return json(['code' => 0, 'msg' => '非法请求']);
    }
}
