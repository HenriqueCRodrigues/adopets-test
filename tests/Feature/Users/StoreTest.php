<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Carbon\Carbon;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Str;
use Spatie\Activitylog\ActivityLogger;

class StoreTest extends TestCase
{
    private function storeUserThroughTheApi(User $user = null) {
        config(['laravel-activitylog.enabled' => false]);
        $user = $user ?? collect([]);
        return $this->post(route('user.store'), $user->toArray());
    }

    private function checkUserDatabaseHas($item) {
        $this->assertDatabaseHas('users', $item);
    }

    private function checkUserDatabaseMissing($item) {
        $this->assertDatabaseMissing('users', $item);
    }

    private function unitInputApi($input, $meta = null) {
        Carbon::setTestNow(now());

        $category = factory(Category::class)->create();

        $value = $meta;
        if ($meta) {
            $meta = explode(':', $meta);
            if ($meta[0] == 'max') {
                $value = Str::random($meta[1]+1);
            } else if ($meta[0] == 'exists') {
                $meta[1] = explode(',', $meta[1]);
                return $this->assertDatabaseMissing($meta[1][0], [$meta[1][1] => '']);
            }
        }

        $user = factory(User::class)->make([$input => $value]);

        $this->storeUserThroughTheApi($user)
        ->assertStatus(422);
        
        $this->checkUserDatabaseMissing(['email' => $user->email, 'created_at' => now(), 'updated_at' => now()]);
    }

    public function it_should_store_in_authenticated() {
        $this->storeUserThroughTheApi()
        ->assertStatus(422);
    }

    /** @test */
    public function it_should_store_in_database() {
        Carbon::setTestNow(now());

        $user = factory(User::class)->make();
        $this->storeUserThroughTheApi($user)
        ->assertSuccessful();

        $this->checkUserDatabaseHas(['email' => $user->email, 'created_at' => now(), 'updated_at' => now()]);
    }
    
    /** @test */
    public function name_input_field_is_required() {
        $this->unitInputApi('name');
    }

    /** @test */
    public function email_input_field_is_required() {
        $this->unitInputApi('email');
    }

    /** @test */
    public function password_input_field_is_required() {
        $this->unitInputApi('password');
    }

    /** @test */
    public function name_input_field_is_max_255() {
        $this->unitInputApi('name', 'max:255');
    }

    /** @test */
    public function email_input_field_exists() {
        $this->unitInputApi('email', 'exists:users,email');
    }

    /** @test */
    public function email_input_field_is_email() {
        $this->unitInputApi('email', 'string');
    }
}
