<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Repository\UserRepository;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{

    public function __construct(
        private readonly UserRepository $userRepository
    )
    {
    }

    public function index()
    {
        $users = User::query()->paginate(10);

        return view('users.index', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(UserStoreRequest $userStoreRequest)
    {
        return redirect()->route(
            'users.show',
            $this->userRepository->store($userStoreRequest)
        );
    }

    public function show(User $user)
    {
        return view('users.show', [
            'user' => $user,
        ]);
    }

    public function update(
        UserUpdateRequest $userUpdateRequest,
        User              $user
    ): RedirectResponse
    {
        return redirect()->route(
            'users.show',
            $this->userRepository->update($userUpdateRequest, $user))
            ->with('success', 'Пользователь успешно обновлен!');
    }

    public function destroy(User $user)
    {
        $userRemoveResult = $this->userRepository->destroy($user);

        if ($userRemoveResult) {
            return redirect()->
            back()->
            with('success', 'Пользователь удален!');
        }

        return redirect()->
        back()->
        withErrors('errors', 'Ошибка при удалении!');
    }
}
