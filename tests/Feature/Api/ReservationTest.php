<?php

namespace Tests\Feature\Api;

use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    function test_store_a_reservation()
    {
        $data = Reservation::factory()->make();

        $params = [
            'room_id'       => $data->room_id,
            'start_date'    => $data->start_date->format('Y-m-d H:i:s'),
            'end_date'      => $data->end_date->format('Y-m-d H:i:s'),
            'emergency'     => $data->emergency,
            'doctor_id'     => $data->doctor_id,
        ];

        $this->post('api/v1/reservations', $params)
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'emergency' => $data->emergency,
                ]
            ]);

        $this->assertDatabaseHas('reservations', [
            'room_id'       => $data->room_id,
            'start_date'    => $data->start_date,
            'end_date'      => $data->end_date,
            'emergency'     => $data->emergency,
            'doctor_id'     => $data->doctor_id,
        ]);
    }

    function test_validate_reservation_at_store()
    {
        $this->postJson('api/v1/reservations')
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'room_id'       => ['El campo sala es obligatorio.'],
                    'start_date'    => ['El campo fecha de inicio es obligatorio.'],
                    'end_date'      => ['El campo fecha de término es obligatorio.'],
                    'doctor_id'     => ['El campo doctor es obligatorio.'],
                ]
            ]);
    }
}
