<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleController extends Controller
{
    public function index(): JsonResource
    {
        $roles = Role::query()->orderBy('name')->get();

        return RoleResource::collection($roles);
    }
}
