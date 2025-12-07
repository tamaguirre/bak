<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\Status;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function store(Request $request): EventResource
    {
        $event = Event::query()->create($request->only(['event_type_id', 'reservation_id']));

        $status = Status::query()->where('event_type_id', $event->event_type_id)->first();
        if ($status) {
            $event->reservation->update(['status_id' => $status->id]);
        }

        return new EventResource($event);
    }
}
