<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;

use App\Classes\ApiResponseClass;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Interfaces\UserRepositoryInterface;

class AuthController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return ApiResponseClass::throw($validator->errors(), 'Validation failed');
        }

        try {
            DB::beginTransaction();
            $user = $this->userRepository->createUser(request()->all());
            DB::commit();

            return ApiResponseClass::sendResponse(
                new UserResource($user),
                'User registered successfully'
            );
        } catch (\Exception $e) {
            return ApiResponseClass::rollback($e);
        }
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return ApiResponseClass::throw(new \Exception('Invalid credentials'), 'Unauthorized');
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return ApiResponseClass::sendResponse(
            new UserResource($this->userRepository->getAuthenticatedUser()),
            'User profile retrieved successfully'
        );
    }

    public function logout()
    {
        auth()->logout();
        return ApiResponseClass::sendResponse(null, 'Successfully logged out');
    }


    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    //update profil 
    public function updateProfile(UpdateProfileRequest $request)
    {
        try{
            $user = Auth::user();

            $data = $request->validated();


            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public');
                $data['image'] = $imagePath;

            }
            
    
            $updatedUser = $this->userRepository->updateUser($data, $user);
            return ApiResponseClass::sendResponse(new UserResource($updatedUser),'Your profile is Updated Successful',201);
        }catch(\Exception $ex){
            return ApiResponseClass::rollback($ex);
        }

    }

    protected function respondWithToken($token)
    {
        return ApiResponseClass::sendResponse([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ], 'Token generated successfully');
    }
}

