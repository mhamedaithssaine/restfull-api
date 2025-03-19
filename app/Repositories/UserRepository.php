<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    protected $user;
    public function __construct(User $user){
        $this->user = $user;
    }

    // create User
    public function createUser(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);
    }

    //find user by email
    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    // get user authenticated
    public function getAuthenticatedUser()
    {
        return auth()->user();
    }

    //update profil user 
    public function updateUser(array $data, $user)
    {
     
        if ($user) {
            $user->update($data);
            return $user;
        }
        return null;
    }
}