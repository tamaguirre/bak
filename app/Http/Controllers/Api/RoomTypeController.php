<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoomTypeResource;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomTypeController extends Controller
{
    public function index(): JsonResource
    {
        $roomTypes = RoomType::query()->orderBy('name')->get();

        return RoomTypeResource::collection($roomTypes);
    }
}
