<?php

namespace App\Repository;

use App\Http\Requests\UserStoreRequest;
use App\Models\User;

class UserRepository
{
    final public function store(UserStoreRequest $userStoreRequest): User
    {
       return User::query()->create($userStoreRequest->validated());
    }
}
