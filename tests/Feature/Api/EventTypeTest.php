<?php

namespace Tests\Feature\Api;

use App\Models\EventType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTypeTest extends TestCase
{
    use RefreshDatabase;

    function test_list_event_types()
    {
        $eventType = EventType::factory()->create();

        $this->get('api/v1/event-types')
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    0 => [
                        'id'    => $eventType->id,
                    ]
                ]
            ]);
    }
}
