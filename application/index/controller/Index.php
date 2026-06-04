<?php
namespace app\index\controller;

use app\common\model\Product;
use app\common\model\ProductCategory;
use app\common\model\News;
use app\common\model\Banner;

class Index extends Base
{
    public function index()
    {
        // 轮播图
        $banners = Banner::where('status', 1)->order('sort', 'asc')->select();
        $this->assign('banners', $banners);

        // 推荐产品
        $recommend_products = Product::where('status', 1)->where('is_recommend', 1)->order('sort', 'asc')->limit(8)->select();
        $this->assign('recommend_products', $recommend_products);

        // 最新新闻
        $latest_news = News::where('status', 1)->order('create_time', 'desc')->limit(4)->select();
        $this->assign('latest_news', $latest_news);

        return $this->fetch();
    }
}
