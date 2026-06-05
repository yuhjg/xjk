<?php
namespace app\admin\controller;

use think\facade\Session;
use app\common\model\Product;
use app\common\model\ProductCategory;
use app\common\model\News;
use app\common\model\NewsCategory;
use app\common\model\Message;

class Index extends Base
{
    public function index()
    {
        $this->assign('admin_name', Session::get('admin_name'));
        return $this->fetch();
    }

    public function welcome()
    {
        $stats = [
            'product_category' => ProductCategory::count(),
            'product'          => Product::count(),
            'news_category'    => NewsCategory::count(),
            'news'             => News::count(),
            'message'          => Message::count(),
            'message_unread'   => Message::where('is_read', 0)->count(),
        ];
        $this->assign('stats', $stats);
        return $this->fetch();
    }
}
