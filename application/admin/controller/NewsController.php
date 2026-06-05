<?php
namespace app\admin\controller;

use app\common\model\News;
use app\common\model\NewsCategory;

class NewsController extends Base
{
    public function index()
    {
        $keyword = $this->request->param('keyword', '', 'trim');
        $category_id = $this->request->param('category_id', 0, 'intval');

        $query = News::with('category');
        if ($keyword) {
            $query->where('title', 'like', '%' . $keyword . '%');
        }
        if ($category_id > 0) {
            $query->where('category_id', $category_id);
        }

        $news_list = $query->order('is_top', 'desc')->order('create_time', 'desc')->paginate(15, false, [
            'query' => ['keyword' => $keyword, 'category_id' => $category_id]
        ]);

        $categories = NewsCategory::where('status', 1)->order('sort', 'asc')->select();

        $this->assign('news_list', $news_list);
        $this->assign('categories', $categories);
        $this->assign('keyword', $keyword);
        $this->assign('category_id', $category_id);
        return $this->fetch();
    }

    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $result = $this->validate($data, [
                'title' => 'require|max:200',
                'category_id' => 'require|number',
            ], [
                'title.require' => '新闻标题不能为空',
                'category_id.require' => '请选择分类',
            ]);

            if (true !== $result) {
                return $this->iframeMsg($result, 0);
            }

            $image = $this->uploadFile('image', 'news');
            if ($image) {
                $data['image'] = $image;
            }

            if (News::create($data)) {
                return $this->iframeMsg('添加成功', 1, '/admin/news');
            }
            return $this->iframeMsg('添加失败', 0);
        }

        $categories = NewsCategory::where('status', 1)->order('sort', 'asc')->select();
        $this->assign('categories', $categories);
        return $this->fetch();
    }

    public function edit()
    {
        $id = $this->request->param('id', 0, 'intval');

        if ($this->request->isPost()) {
            $data = $this->request->post();
            $result = $this->validate($data, [
                'title' => 'require|max:200',
                'category_id' => 'require|number',
            ], [
                'title.require' => '新闻标题不能为空',
                'category_id.require' => '请选择分类',
            ]);

            if (true !== $result) {
                return $this->iframeMsg($result, 0);
            }

            $image = $this->uploadFile('image', 'news');
            if ($image) {
                $data['image'] = $image;
            } else {
                unset($data['image']);
            }

            if (News::where('id', $id)->update($data) !== false) {
                return $this->iframeMsg('修改成功', 1, '/admin/news');
            }
            return $this->iframeMsg('修改失败', 0);
        }

        $news = News::get($id);
        if (!$news) {
            $this->error('新闻不存在');
        }

        $categories = NewsCategory::where('status', 1)->order('sort', 'asc')->select();
        $this->assign('news', $news);
        $this->assign('categories', $categories);
        return $this->fetch();
    }

    public function delete()
    {
        $id = $this->request->param('id', 0, 'intval');
        if (News::destroy($id)) {
            return $this->successJson('删除成功');
        }
        return $this->errorJson('删除失败');
    }

    public function setStatus()
    {
        $id = $this->request->param('id', 0, 'intval');
        $status = $this->request->param('status', 0, 'intval');

        if (News::where('id', $id)->update(['status' => $status]) !== false) {
            return $this->successJson('操作成功');
        }
        return $this->errorJson('操作失败');
    }
}
