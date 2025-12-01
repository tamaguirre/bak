<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationStore;
use App\Http\Requests\ReservationUpdate;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationController extends Controller
{
    public function index(): JsonResource
    {
        $reservations = Reservation::query()
            ->with(['room.type', 'doctor'])
            ->byRoom()
            ->get();

        return ReservationResource::collection($reservations);
    }

    public function store(ReservationStore $request)
    {
        $reservation = Reservation::query()->create($request->only([
            'room_id', 'start_date', 'end_date', 'emergency', 'doctor_id',
        ]));

        return new ReservationResource($reservation);
    }

    public function update(ReservationUpdate $request, $reservationId)
    {
        $reservation = Reservation::query()->find($reservationId);

        $reservation->update(request()->only(['start_date', 'end_date']));

        return new ReservationResource($reservation);
    }

    public function destroy($reservationId)
    {
        Reservation::query()->where('id', $reservationId)->delete();

        return response()->noContent();
    }
}
