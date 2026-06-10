<?php
namespace app\index\controller;

use app\common\model\Image;

class Factory extends Base
{
    public function index()
    {
        // 从图片管理中按分类读取"走进工厂"的图片
        $factoryImages = Image::where('status', 1)
            ->where('category', '走进工厂')
            ->order('sort', 'asc')
            ->order('id', 'asc')
            ->select();
        $this->assign('factoryImages', $factoryImages);

        // 同时读取其他分类展示（车间、设备、实验室等）
        $categories = Image::where('status', 1)
            ->where('category', 'like', '工厂%')
            ->group('category')
            ->column('category');
        $this->assign('factoryCategories', $categories);

        return $this->fetch();
    }
}
