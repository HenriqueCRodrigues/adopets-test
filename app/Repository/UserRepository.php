<?php 

namespace App\Repository;

use App\Models\User;

class UserRepository
{
  public function store($data) {
    try {
      \DB::beginTransaction();

      $user = User::create($data);

      $accessToken = $user->createToken('authToken')->accessToken;
      \DB::commit();
  
      return ['X-Access-Token' => $accessToken, 'status' => 200];
    } catch (\Exception $e)
    {
      \DB::rollback();
      return ['message' => $e->getMessage(), 'status' => 500];
    }
  }

  public function me() {
    return \Auth::user();
  }

  public function login($data) {
    try {
      $loginData = $data->validate([
        'email' => 'email|required',
        'password' => 'required'
      ]);

      if (!auth()->attempt($loginData)) {
          return ['message' => 'Invalid Credentials', 'status' => 401];
      }

      $accessToken = auth()->user()->createToken('authToken')->accessToken;

      return ['X-Access-Token' => $accessToken, 'status' => 200];
    } catch (\Exception $e)
    {
      return ['message' => $e->getMessage(), 'status' => 500];
    }
  }

  public function logout() {
    try {
        if (\Auth::check()) {
        $token = \Auth::user()->token();
        $token->revoke();
        return ['message' => 'User is logout', 'status' => 200];
      }
      
      return ['message' => 'Invalid request', 'status' => 422];
    } catch (\Exception $e)
    {
      return ['message' => $e->getMessage(), 'status' => 500];
    }
  }
}