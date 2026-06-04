<?php
namespace app\common\model;

use think\Model;

class Product extends Model
{
    protected $name = 'product';
    protected $autoWriteTimestamp = true;

    public function category()
    {
        return $this->belongsTo('ProductCategory', 'category_id');
    }

    public function getCategoryNameAttr($value, $data)
    {
        $category = ProductCategory::get($data['category_id']);
        return $category ? $category->name : '';
    }
}
