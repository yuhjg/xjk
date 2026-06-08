<?php
namespace app\index\controller;

use app\common\model\Product as ProductModel;
use app\common\model\ProductCategory;

class Product extends Base
{
    public function index()
    {
        $category_id = $this->request->param('category_id', 0, 'intval');
        $keyword = $this->request->param('keyword', '', 'trim');

        // 获取分类树
        $categoryTree = ProductCategory::getCategoryTree();
        $this->assign('categoryTree', $categoryTree);

        // 当前分类信息
        $currentCategory = null;
        if ($category_id > 0) {
            $currentCategory = ProductCategory::get($category_id);
        }
        $this->assign('currentCategory', $currentCategory);

        // 产品列表 - 如果选的是一级分类，则包含其下所有子分类的产品
        $where = ['status' => 1];
        if ($category_id > 0) {
            $categoryIds = ProductCategory::getCategoryIdsWithChildren($category_id);
            $query = ProductModel::where($where)->whereIn('category_id', $categoryIds);
        } else {
            $query = ProductModel::where($where);
        }

        if ($keyword) {
            $query->where('title', 'like', '%' . $keyword . '%');
        }
        $products = $query->order('sort', 'asc')->order('id', 'desc')->paginate(12, false, [
            'query' => ['category_id' => $category_id, 'keyword' => $keyword]
        ]);

        $this->assign('products', $products);
        $this->assign('category_id', $category_id);
        $this->assign('keyword', $keyword);

        return $this->fetch();
    }

    public function detail()
    {
        $id = $this->request->param('id', 0, 'intval');
        $product = ProductModel::get($id);
        if (!$product || $product->status != 1) {
            $this->error('产品不存在');
        }

        // 同类产品（含同父分类下的产品）
        $categoryId = $product->category_id;
        $category = ProductCategory::get($categoryId);
        $relatedCategoryId = $categoryId;
        // 如果是二级分类，也显示同父分类下的其他产品
        if ($category && $category->parent_id > 0) {
            $relatedCategoryId = $category->parent_id;
            $categoryIds = ProductCategory::getCategoryIdsWithChildren($relatedCategoryId);
            $related = ProductModel::where('status', 1)->whereIn('category_id', $categoryIds)->where('id', '<>', $id)->limit(4)->select();
        } else {
            $related = ProductModel::where('status', 1)->where('category_id', $categoryId)->where('id', '<>', $id)->limit(4)->select();
        }

        // 分类树（侧边栏用）
        $categoryTree = ProductCategory::getCategoryTree();
        $this->assign('categoryTree', $categoryTree);

        $this->assign('product', $product);
        $this->assign('related', $related);
        return $this->fetch();
    }
}
