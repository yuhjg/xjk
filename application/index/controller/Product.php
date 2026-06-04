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

        // 所有分类
        $categories = ProductCategory::where('status', 1)->order('sort', 'asc')->select();
        $this->assign('categories', $categories);

        // 产品列表
        $where = ['status' => 1];
        if ($category_id > 0) {
            $where['category_id'] = $category_id;
        }
        $query = ProductModel::where($where);
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

        // 同类产品
        $related = ProductModel::where('status', 1)->where('category_id', $product->category_id)->where('id', '<>', $id)->limit(4)->select();

        $this->assign('product', $product);
        $this->assign('related', $related);
        return $this->fetch();
    }
}
