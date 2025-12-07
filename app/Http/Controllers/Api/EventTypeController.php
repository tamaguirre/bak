<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventTypeResource;
use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventTypeController extends Controller
{
    public function index(): JsonResource
    {
        $eventTypes = EventType::query()->get();

        return EventTypeResource::collection($eventTypes);
    }
}
