<?php

namespace Tests\Feature\Products;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Carbon\Carbon;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Str;

class UpdateTest extends TestCase
{
    private function updateProductThroughTheApi($uuid, Product $product = null) {
        config(['laravel-activitylog.enabled' => false]);
        $product = $product ?? collect([]);
        return $this->post(route('product.update', ['uuid' => $uuid]), $product->toArray());
    }

    private function findProductThroughTheApi() {
        return $this->post(route('product.find'));
    }

    private function loginUserThroughTheApi($user) {
        $token = $user->createToken(['TestToken'], [])->accessToken;

        return $this->withHeaders([
            'X-Access-Token' => $token,
        ]);
    }

    private function checkProductDatabaseHas($item, $merged) {
        $item = array_merge($item->toArray(), $merged);
        $this->assertDatabaseHas('products', $item);
    }

    private function checkProductDatabaseMissing($item, $merged) {
        $item = array_merge($item->toArray(), $merged);
        $this->assertDatabaseMissing('products', $item);
    }

    private function unitInputApi($input, $meta = null) {
        Carbon::setTestNow(now());

        $category = factory(Category::class)->create();
        $user = factory(User::class)->create();

        $merge = ['user_id' => $user->id, 'category_id' => $category->id];
        $product = factory(Product::class)->create($merge);

        $product[$input] = $meta;
        $merge[$input] = $meta;

        if ($meta) {
            $meta = explode(':', $meta);
            if ($meta[0] == 'max') {
                $product[$input] = Str::random($meta[1]+1);
            } else if ($meta[0] == 'exists') {
                $meta[1] = explode(',', $meta[1]);
                return $this->assertDatabaseMissing($meta[1][0], [$meta[1][1] => '']);
            } else {
                $product[$input] = $meta[0];
            }
        }

        $this->loginUserThroughTheApi($user)
        ->updateProductThroughTheApi($product->uuid, $product)
        ->assertStatus(422);
        
        $merge['created_at'] = now(); 
        $merge['updated_at'] = now();
        $this->checkProductDatabaseMissing($product, $merge);
    }

    /** @test */
    public function it_should_update_in_authenticated() {
        $category = factory(Category::class)->create();
        $user = factory(User::class)->create();

        $product = factory(Product::class)->create(['user_id' => $user->id, 'category_id' => $category->id]);

        $this->updateProductThroughTheApi($product->uuid, $product)
        ->assertUnauthorized();
    }


    /** @test */
    public function it_should_update_in_other_user() {
        Carbon::setTestNow(now());

        $category = factory(Category::class)->create();
        $user = factory(User::class)->create();

        $product = factory(Product::class)->create(['user_id' => $user->id, 'category_id' => $category->id]);

        $otherUser = factory(User::class)->create();


        $this->loginUserThroughTheApi($otherUser)
        ->updateProductThroughTheApi($product->uuid, $product)
        ->assertStatus(422);
    }

    /** @test */
    public function it_should_update_in_database() {
        Carbon::setTestNow(now());

        $category = factory(Category::class)->create();
        $user = factory(User::class)->create();

        $product = factory(Product::class)->create(['user_id' => $user->id, 'category_id' => $category->id]);

        $this->loginUserThroughTheApi($user)
        ->updateProductThroughTheApi($product->uuid, $product)
        ->assertSuccessful();

        $this->checkProductDatabaseHas($product, ['created_at' => now(), 'updated_at' => now()]);
    }

    /** @test */
    public function name_input_field_is_required() {
        $this->unitInputApi('name');
    }

     /** @test */
     public function description_input_field_is_required() {
        $this->unitInputApi('description');
    }

    /** @test */
    public function category_id_input_field_is_required() {
        $this->unitInputApi('category_id');
    }

    /** @test */
    public function price_input_field_is_required() {
        $this->unitInputApi('price');
    }

    /** @test */
    public function stock_input_field_is_required() {
        $this->unitInputApi('stock');
    }

    /** @test */
    public function name_input_field_is_max_255() {
        $this->unitInputApi('name', 'max:255');
    }

    /** @test */
    public function category_id_input_field_exists() {
        $this->unitInputApi('category_id', 'exists:categories,id');
    }

    /** @test */
    public function price_input_field_is_numeric() {
        $this->unitInputApi('price', 'string');
    }
    
    /** @test */
    public function stock_input_field_is_integer() {
        $this->unitInputApi('stock', 'string');
    }
}
