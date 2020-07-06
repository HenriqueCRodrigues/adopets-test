<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Repository\UserRepository;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserRequest;
use App\Http\Responses\GenericResponse;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function store(UserRequest $request) {
        $user = $this->userRepository->store($request->all());

        return GenericResponse::response($delete);
    } 
    
    public function me() {
        $user = $this->userRepository->me();

        return new UserResource($user);
    } 

    public function login(Request $request)
    {
        $user = $this->userRepository->login($request);

        return GenericResponse::response($delete);
    }
    
    public function logout(Request $request) {
        $logout = $this->userRepository->logout();

        return GenericResponse::response($delete);
    }   
}
