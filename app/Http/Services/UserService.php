<?php

namespace App\Http\Services;

use App\Models\User;

class UserService {

    public function store(array $data): User
    {
        $user = User::create($data);

        return $user;
    }

    public function update(array $data, User $user): User
    {
        $user->update($data);

        return $user;
    }
}