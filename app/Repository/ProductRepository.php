<?php 

namespace App\Repository;

use App\Models\Product;

class ProductRepository
{
  public function store($data) {
    \DB::beginTransaction();
    
    $product = Product::create($data);
    \DB::commit();

    return $product;
  }

  public function update($uuid, $data) {
    \DB::beginTransaction();

    $product = Product::where('uuid', $uuid)->first();
    if ($product) {
      if($product->user_id == \Auth::id()) { //Manual comparison due to logs
        $product->update($data);
      } else {
        $product = false;
      }
  
      if ($product) {
        \DB::commit();
        return ['message' => 'Updated product', 'status' => 200];
      }
    }
   
    \DB::rollback();
    return ['message' => 'Product not updated', 'status' => 422];
  }

  public function findProducts($data) {
    $q = \Request::get('q');
    $page = \Request::get('page') ?? 1;
    $per_page = \Request::get('per_page') ?? 10;
    
    $products = Product::join('categories', 'categories.id', 'products.category_id')
    ->where('user_id', \Auth::id())
    ->when($q, function ($query) use ($q) {
      $query->where('products.name', 'LIKE', '%'.$q.'%');
      $query->orWhere('description', 'LIKE', '%'.$q.'%');
      $query->orWhere('categories.name', 'LIKE', '%'.$q.'%');
    })
    ->select('products.*')
    ->paginate($per_page);

    return $products;
  }


  public function delete($uuid) {
    \DB::beginTransaction();
    
    $product = Product::where('uuid', $uuid);
    if (!$product->first()) {
      return ['message' => 'Product not found', 'status' => 404];
    }

    if ($product->where('user_id', \Auth::id())->first()) {
      if ($product->delete()) {
        \DB::commit();
        return ['message' => 'Deleted product', 'status' => 200];
      }
  
      return ['message' => 'Product not deleted', 'status' => 422];  
    }

    \DB::rollback();
    return ['message' => 'Unauthorized', 'status' => 401];  
  }
}