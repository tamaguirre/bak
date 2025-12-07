<?php

namespace Feature\Api;

use App\Models\Shift;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShiftTest extends TestCase
{
    use RefreshDatabase;

    function test_list_shifts()
    {
        $shift = Shift::factory()->create();

        $this->get('api/v1/shifts')
            ->assertStatus(200)
            ->assertJson([
                'data'  => [
                    0   => [
                        'id'    => $shift->id,
                        'type'  => [],
                        'user'  => []
                    ]
                ]
            ]);
    }

    function test_list_shifts_by_doctor()
    {
        $shift = Shift::factory()->create();

        $params = [
            'user_id'   => $shift->user_id
        ];

        $this->call('GET','api/v1/shifts', $params)
            ->assertStatus(200)
            ->assertJson([
                'data'  => [
                    0   => [
                        'id'    => $shift->id,
                        'type'  => [],
                        'user'  => []
                    ]
                ]
            ]);
    }

    function test_store_a_shift()
    {
        $data = Shift::factory()->make();

        $params = [
            'user_id'       => $data->user_id,
            'shift_type_id' => $data->shift_type_id,
            'date'          => $data->date
        ];

        $this->post('api/v1/shifts', $params)
            ->assertStatus(201);

        $this->assertDatabaseHas('shifts', [
            'user_id'       => $data->user_id,
            'shift_type_id' => $data->shift_type_id,
        ]);
    }
}
