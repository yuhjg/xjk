<?php
namespace app\admin\controller;

use app\common\model\Message as MessageModel;

class Message extends Base
{
    public function index()
    {
        $keyword = $this->request->param('keyword', '', 'trim');
        $is_read = $this->request->param('is_read', -1, 'intval');

        $query = MessageModel::order('is_read', 'asc')->order('create_time', 'desc');
        if ($keyword) {
            $query->where('name|phone', 'like', '%' . $keyword . '%');
        }
        if ($is_read >= 0) {
            $query->where('is_read', $is_read);
        }

        $messages = $query->paginate(15, false, [
            'query' => ['keyword' => $keyword, 'is_read' => $is_read]
        ]);

        $this->assign('messages', $messages);
        $this->assign('keyword', $keyword);
        $this->assign('is_read', $is_read);
        return $this->fetch();
    }

    public function detail()
    {
        $id = $this->request->param('id', 0, 'intval');
        $message = MessageModel::get($id);
        if (!$message) {
            return $this->errorJson('留言不存在');
        }

        // 标记已读
        if ($message->is_read == 0) {
            $message->save(['is_read' => 1]);
        }

        $this->assign('message', $message);
        return $this->fetch();
    }

    public function delete()
    {
        $id = $this->request->param('id', 0, 'intval');
        if (MessageModel::destroy($id)) {
            return $this->successJson('删除成功');
        }
        return $this->errorJson('删除失败');
    }

    public function setRead()
    {
        $id = $this->request->param('id', 0, 'intval');
        if (MessageModel::where('id', $id)->update(['is_read' => 1]) !== false) {
            return $this->successJson('操作成功');
        }
        return $this->errorJson('操作失败');
    }
}
