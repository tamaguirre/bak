<?php

namespace Feature\Api;

use App\Models\Event;
use App\Models\EventType;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    function test_store_a_event()
    {
        $eventType = EventType::factory()->create();
        $data = Event::factory()->make([
            'event_type_id' => $eventType->id
        ]);
        $status = Status::factory()->create([
            'event_type_id'  => $eventType->id
        ]);

        $params = [
            'event_type_id'     => $data->event_type_id,
            'reservation_id'    => $data->reservation_id,
        ];

        $this->post('api/v1/events', $params)
            ->assertStatus(201);

        $this->assertDatabaseHas('events', [
            'event_type_id'     => $data->event_type_id,
            'reservation_id'    => $data->reservation_id,
        ]);

        $this->assertDatabaseHas('reservations', [
            'id'        => $data->reservation_id,
            'status_id' => $status->id,
        ]);
    }
}
