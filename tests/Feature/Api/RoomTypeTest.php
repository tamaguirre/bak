<?php

namespace Tests\Feature\Api;

use App\Models\RoomType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomTypeTest extends TestCase
{
    use RefreshDatabase;

    function test_list_room_types()
    {
        $roomType = RoomType::factory()->create();

        $this->get('api/v1/room-types')
            ->assertStatus(200)
            ->assertJson([
                'data'  => [
                    0   => [
                        'id'    => $roomType->id
                    ]
                ]
            ]);
    }
}
