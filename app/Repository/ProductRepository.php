<?php 

namespace App\Repository;

use App\Models\Product;

class ProductRepository
{
  public function store($data) {
    try {
      \DB::beginTransaction();
     
      $product = Product::create($data);
      \DB::commit();

      return $product;
    } catch (\Exception $e)
    {
      \DB::rollback();
      return ['message' => $e->getMessage(), 'status' => 500];
    }
  }

  public function update($uuid, $data) {
    try {
      \DB::beginTransaction();

      $product = Product::where('uuid', $uuid)->where('user_id', \Auth::id())->update($data);
      if ($product) {
        \DB::commit();
        return ['message' => 'Updated product', 'status' => 200];
      }

      return ['message' => 'Product not updated', 'status' => 422];
    } catch (\Exception $e)
    {
      \DB::rollback();
      return ['message' => $e->getMessage(), 'status' => 500];
    }
  }

  public function findProducts($data) {
    try {
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
    } catch (\Exception $e)
    {
      return ['message' => $e->getMessage(), 'status' => 500];
    }
  }


  public function delete($uuid) {
    try {
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

      return ['message' => 'Unauthorized', 'status' => 401];  
    } catch (\Exception $e)
    {
      \DB::rollback();
      return ['message' => $e->getMessage(), 'status' => 500];
    }
  }
}