<?php
namespace app\index\controller;

use app\common\model\News as NewsModel;
use app\common\model\NewsCategory;

class News extends Base
{
    public function index()
    {
        $category_id = $this->request->param('category_id', 0, 'intval');

        // 所有分类
        $categories = NewsCategory::where('status', 1)->order('sort', 'asc')->select();
        $this->assign('categories', $categories);

        // 新闻列表
        $where = ['status' => 1];
        if ($category_id > 0) {
            $where['category_id'] = $category_id;
        }
        $news_list = NewsModel::where($where)->order('is_top', 'desc')->order('create_time', 'desc')->paginate(10, false, [
            'query' => ['category_id' => $category_id]
        ]);

        $this->assign('news_list', $news_list);
        $this->assign('category_id', $category_id);

        return $this->fetch();
    }

    public function detail()
    {
        $id = $this->request->param('id', 0, 'intval');
        $news = NewsModel::get($id);
        if (!$news || $news->status != 1) {
            $this->error('新闻不存在');
        }

        // 浏览量+1
        $news->where('id', $id)->setInc('views');

        // 上一篇
        $prev = NewsModel::where('status', 1)->where('id', '<', $id)->order('id', 'desc')->find();
        // 下一篇
        $next = NewsModel::where('status', 1)->where('id', '>', $id)->order('id', 'asc')->find();

        $this->assign('news', $news);
        $this->assign('prev', $prev);
        $this->assign('next', $next);
        return $this->fetch();
    }
}
