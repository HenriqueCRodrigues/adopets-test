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

class DeleteTest extends TestCase
{
    private function deleteProductThroughTheApi($uuid, Product $product = null) {
        config(['laravel-activitylog.enabled' => false]);
        $product = $product ?? collect([]);
        return $this->delete(route('product.delete', ['uuid' => $uuid]), $product->toArray());
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

    /** @test */
    public function it_should_delete_in_authenticated() {
        $category = factory(Category::class)->create();
        $user = factory(User::class)->create();

        $product = factory(Product::class)->create(['user_id' => $user->id, 'category_id' => $category->id]);

        $this->deleteProductThroughTheApi($product->uuid, $product)
        ->assertUnauthorized();
    }

    /** @test */
    public function it_should_delete_in_other_user() {
        Carbon::setTestNow(now());

        $category = factory(Category::class)->create();
        $user = factory(User::class)->create();

        $product = factory(Product::class)->create(['user_id' => $user->id, 'category_id' => $category->id]);

        $otherUser = factory(User::class)->create();


        $this->loginUserThroughTheApi($otherUser)
        ->deleteProductThroughTheApi($product->uuid, $product)
        ->assertUnauthorized();
    }

    /** @test */
    public function it_should_delete_in_database() {
        Carbon::setTestNow(now());

        $category = factory(Category::class)->create();
        $user = factory(User::class)->create();

        $product = factory(Product::class)->create(['user_id' => $user->id, 'category_id' => $category->id]);

        $this->loginUserThroughTheApi($user)
        ->deleteProductThroughTheApi($product->uuid, $product)
        ->assertSuccessful();

        $this->checkProductDatabaseHas($product, ['created_at' => now(), 'updated_at' => now(), 'deleted_at' => now()]);
    }
}
