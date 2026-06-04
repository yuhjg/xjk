<?php
namespace app\common\model;

use think\Model;

class NewsCategory extends Model
{
    protected $name = 'news_category';
    protected $autoWriteTimestamp = true;

    public function newsList()
    {
        return $this->hasMany('News', 'category_id');
    }
}
