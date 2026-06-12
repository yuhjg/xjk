<?php
namespace app\index\controller;

use think\Controller;
use app\common\model\Product;
use app\common\model\ProductCategory;
use app\common\model\News;
use app\common\model\Banner;
use app\common\model\Company;

class Base extends Controller
{
    protected $company;

    protected function initialize()
    {
        parent::initialize();
        // 获取公司信息
        $this->company = Company::find(1);
        $this->assign('company', $this->company);

        // 获取产品分类（导航用）
        $categories = ProductCategory::where('status', 1)->where('parent_id', 0)->order('sort', 'asc')->select();
        $this->assign('nav_categories', $categories);

        // 当前控制器名（用于导航高亮）
        $controller = strtolower($this->request->controller());
        $this->assign('current_controller', $controller);
    }
}
