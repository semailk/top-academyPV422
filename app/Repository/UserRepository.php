<?php

namespace App\Repository;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Avatar;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserRepository
{
    final public function store(UserStoreRequest $userStoreRequest): User
    {
        DB::beginTransaction();
        try {
            //TODO Сделать на следующем уроке без путей а только с названием картинки
            $filePath = 'storage/' . $userStoreRequest->file('avatar')->store('avatars', 'public');
            $user = User::query()->create($userStoreRequest->validated());

            Avatar::query()->create([
                'user_id' => $user->id,
                'path' => $filePath,
            ]);
            DB::commit();
        }catch (\Exception $exception){
            Log::critical($exception->getMessage());
            DB::rollBack();
            throw new BadRequestHttpException($exception->getMessage());
        }

        return $user;
    }

    final public function update(UserUpdateRequest $userUpdateRequest, User $user): User
    {
        $validated = $userUpdateRequest->validated();
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = $validated['password'];
        }
        $user->save();

        return $user->refresh();
    }

    final public function destroy(User $user): bool
    {
       return $user->delete();
    }
}
