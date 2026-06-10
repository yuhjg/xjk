<?php
namespace app\index\controller;

use app\common\model\Company;
use app\common\model\Image;

class Factory extends Base
{
    public function index()
    {
        $company = Company::find(1);
        $this->assign('company', $company);

        // 从图片管理中按分类读取工厂相关图片
        // 优先读取"走进工厂"分类，再读"工厂%"开头的分类
        $factoryCategories = Image::where('status', 1)
            ->where('category', 'like', '工厂%')
            ->group('category')
            ->order('category', 'asc')
            ->column('category');

        // 如果没有工厂%分类，也尝试读取"走进工厂"
        if (empty($factoryCategories)) {
            $factoryCategories = Image::where('status', 1)
                ->where('category', '走进工厂')
                ->group('category')
                ->column('category');
        }

        $this->assign('factoryCategories', $factoryCategories);

        return $this->fetch();
    }
}
