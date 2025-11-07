<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomStore;
use App\Http\Requests\RoomUpdate;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class RoomController extends Controller
{
    public function index(): JsonResource
    {
        $rooms = Room::query()->with('type')->orderBy('name')->get();

        return RoomResource::collection($rooms);
    }

    public function store(RoomStore $request): RoomResource
    {
        $room = Room::query()->create($request->only(['name', 'type_id']));

        return new RoomResource($room);
    }

    public function update(RoomUpdate $request, $roomId)
    {
        $room = Room::query()->find($roomId);

        $room->update($request->only(['name', 'type_id']));

        return new RoomResource($room);
    }


    public function destroy($roomId): Response
    {
        Room::query()->where('id', $roomId)->delete();

        return response()->noContent();
    }
}
