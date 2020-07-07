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

class FindTest extends TestCase
{
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

    /** @test */
    public function it_should_find_in_authenticated() {
        $category = factory(Category::class)->create();
        $user = factory(User::class)->create();

        $product = factory(Product::class)->create(['user_id' => $user->id, 'category_id' => $category->id]);

        $this->findProductThroughTheApi()
        ->assertUnauthorized();
    }


    /** @test */
    public function it_should_find_in_database() {
        Carbon::setTestNow(now());

        $category = factory(Category::class)->create();
        $user = factory(User::class)->create();

        $product = factory(Product::class)->create(['user_id' => $user->id, 'category_id' => $category->id]);

        $this->loginUserThroughTheApi($user)
        ->findProductThroughTheApi()
        ->assertSuccessful();

        $this->checkProductDatabaseHas($product, ['created_at' => now(), 'updated_at' => now()]);
    }
}
