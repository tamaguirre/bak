<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShiftResource;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShiftController extends Controller
{
    public function index(): JsonResource
    {
        $shifts = Shift::query()
            ->with(['type', 'user'])
            ->byDoctor()
            ->get();

        return ShiftResource::collection($shifts);
    }

    public function store(Request $request): ShiftResource
    {
        $shift = Shift::query()->create($request->only(['date', 'user_id', 'shift_type_id']));

        return new ShiftResource($shift);
    }
}
