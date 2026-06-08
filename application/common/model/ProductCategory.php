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

    // 子分类
    public function children()
    {
        return $this->hasMany('ProductCategory', 'parent_id', 'id')->order('sort', 'asc');
    }

    // 父分类
    public function parent()
    {
        return $this->belongsTo('ProductCategory', 'parent_id', 'id');
    }

    // 获取分类名称含层级
    public function getDisplayNameAttr($value, $data)
    {
        if ($data['parent_id'] > 0) {
            $parent = ProductCategory::get($data['parent_id']);
            if ($parent) {
                return $parent->name . ' - ' . $data['name'];
            }
        }
        return $data['name'];
    }

    // 获取所有一级分类
    public static function getTopCategories()
    {
        return self::where('status', 1)->where('parent_id', 0)->order('sort', 'asc')->select();
    }

    // 获取分类树（含子分类）
    public static function getCategoryTree()
    {
        $topCategories = self::where('status', 1)->where('parent_id', 0)->order('sort', 'asc')->select();
        foreach ($topCategories as $cat) {
            $cat->children_list = self::where('status', 1)->where('parent_id', $cat->id)->order('sort', 'asc')->select();
        }
        return $topCategories;
    }

    // 获取某分类及其所有子分类ID
    public static function getCategoryIdsWithChildren($category_id)
    {
        $ids = [$category_id];
        $children = self::where('parent_id', $category_id)->column('id');
        if ($children) {
            $ids = array_merge($ids, $children);
        }
        return $ids;
    }
}
