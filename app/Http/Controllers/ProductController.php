<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Repository\ProductRepository;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Responses\GenericResponse;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
    }

    public function store(ProductRequest $request) {
        $product = $this->productRepository->store($request->all());

        return new ProductResource($product);
    }   

    public function update($uuid, ProductRequest $request) {
        $product = $this->productRepository->update($uuid, $request->all());

        return GenericResponse::response($delete);
    }   
    public function findProducts(Request $request) {
        $products = $this->productRepository->findProducts($request);

        return ProductResource::collection($products);
    }

    public function delete($uuid) {
        $delete = $this->productRepository->delete($uuid);

        return GenericResponse::response($delete);
    }   
}
