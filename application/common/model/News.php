<?php
namespace app\common\model;

use think\Model;

class News extends Model
{
    protected $name = 'news';
    protected $autoWriteTimestamp = true;

    public function category()
    {
        return $this->belongsTo('NewsCategory', 'category_id');
    }

    public function getCategoryNameAttr($value, $data)
    {
        $category = NewsCategory::get($data['category_id']);
        return $category ? $category->name : '';
    }
}
