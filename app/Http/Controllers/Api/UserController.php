<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStore;
use App\Http\Requests\UserUpdate;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function index(): JsonResource
    {
        $users = User::query()->with('role')->orderBy('name')->get();

        return UserResource::collection($users);
    }

    public function store(UserStore $request): UserResource
    {
        $user = User::query()->create($request->only(['name', 'email', 'role_id', 'password']));

        return new UserResource($user);
    }

    public function update(UserUpdate $request, $userId): UserResource
    {
        $user = User::query()->find($userId);

        $user->update($request->only(['name', 'email', 'role_id', 'password']));

        return new UserResource($user);
    }

    public function destroy($userId): Response
    {
        User::query()->where('id', $userId)->delete();

        return response()->noContent();
    }
}
