<?php

namespace Tests\Feature\Api;

use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomTest extends TestCase
{
    use RefreshDatabase;

    function test_list_rooms()
    {
        $room = Room::factory()->create();

        $this->get('api/v1/rooms')
            ->assertStatus(200)
            ->assertJson([
                'data'  => [
                    0   => [
                        'id'    => $room->id,
                        'type'  => []
                    ]
                ]
            ]);
    }

    function test_store_a_room()
    {
        $data = Room::factory()->make();

        $params = [
            'name'      => $data->name,
            'type_id'   => $data->type_id
        ];

        $this->post('api/v1/rooms', $params)
            ->assertStatus(201)
            ->assertJson([
                'data'  => [
                    'name'  => $data->name
                ]
            ]);

        $this->assertDatabaseHas('rooms', [
            'name'      => $data->name,
            'type_id'   => $data->type_id
        ]);
    }

    function test_validate_room_at_store()
    {
        $this->postJson('api/v1/rooms')
            ->assertStatus(422)
            ->assertJson([
                'errors'  => [
                    'name'      => ['El campo nombre es obligatorio.'],
                    'type_id'   => ['El campo tipo es obligatorio.'],
                ]
            ]);
    }

    function test_update_a_room()
    {
        $room = Room::factory()->create();
        $data = Room::factory()->make();

        $params = [
            'name'      => $data->name,
            'type_id'   => $data->type_id
        ];

        $this->put('api/v1/rooms/'.$room->id, $params)
            ->assertStatus(200)
            ->assertJson([
                'data'  => [
                    'id'  => $room->id
                ]
            ]);

        $this->assertDatabaseHas('rooms', [
            'id'        => $room->id,
            'name'      => $data->name,
            'type_id'   => $data->type_id
        ]);
    }

    function test_validate_room_at_update()
    {
        $room = Room::factory()->create();

        $this->putJson('api/v1/rooms/'.$room->id)
            ->assertStatus(422)
            ->assertJson([
                'errors'  => [
                    'name'      => ['El campo nombre es obligatorio.'],
                    'type_id'   => ['El campo tipo es obligatorio.'],
                ]
            ]);
    }

    function test_destroy_a_room()
    {
        $room = Room::factory()->create();

        $this->delete('api/v1/rooms/'.$room->id)
            ->assertStatus(204);

        $this->assertSoftDeleted('rooms', [
            'id'    => $room->id,
        ]);
    }
}
