<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function createUser(array $data);
    public function findByEmail(string $email);
    public function getAuthenticatedUser();
    public function updateUser(array $data, $user);
  
}