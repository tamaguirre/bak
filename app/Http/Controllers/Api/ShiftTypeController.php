<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShiftTypeResource;
use App\Models\ShiftType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShiftTypeController extends Controller
{
    public function index(): JsonResource
    {
        $shiftTypes = ShiftType::query()->get();

        return ShiftTypeResource::collection($shiftTypes);
    }
}
