<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationStore;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function store(ReservationStore $request)
    {
        $reservation = Reservation::query()->create($request->only([
            'room_id', 'start_date', 'end_date', 'emergency', 'doctor_id',
        ]));

        return new ReservationResource($reservation);
    }
}
