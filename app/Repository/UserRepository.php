<?php 

namespace App\Repository;

use App\Models\User;

class UserRepository
{
  public function store($data) {
    \DB::beginTransaction();

    $user = User::create($data);

    $accessToken = $user->createToken('authToken')->accessToken;
   
    \DB::commit();
    return ['message' => ['X-Access-Token' => $accessToken], 'status' => 200];
  }

  public function me() {
    return \Auth::user();
  }

  public function login($data) {
    $loginData = $data->validate([
      'email' => 'email|required',
      'password' => 'required'
    ]);

    if (!auth()->attempt($loginData)) {
        return ['message' => 'Invalid Credentials', 'status' => 401];
    }

    $accessToken = auth()->user()->createToken('authToken')->accessToken;

    return ['message' => ['X-Access-Token' => $accessToken], 'status' => 200];
  }

  public function logout() {
    if (\Auth::check()) {
      $token = \Auth::user()->token();
      $token->revoke();
      return ['message' => 'User is logout', 'status' => 200];
    }
    
    return ['message' => 'Invalid request', 'status' => 422];
  }
}