<?php
namespace app\common\model;

use think\Model;

class ProductCategory extends Model
{
    protected $name = 'product_category';
    protected $autoWriteTimestamp = true;

    public function products()
    {
        return $this->hasMany('Product', 'category_id');
    }
}
