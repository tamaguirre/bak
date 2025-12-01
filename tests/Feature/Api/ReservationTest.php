<?php

namespace Tests\Feature\Api;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    function test_list_reservations()
    {
        $reservation = Reservation::factory()->create();

        $this->get('api/v1/reservations')
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'id'        => $reservation->id,
                        'room'      => [],
                        'doctor'    => []
                    ]
                ]
            ]);
    }

    function test_list_reservations_by_room_id()
    {
        $room = Room::factory()->create();
        $reservation = Reservation::factory()->create([
            'room_id' => $room->id,
        ]);

        $this->get('api/v1/reservations')
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'id'        => $reservation->id,
                        'room'      => [],
                        'doctor'    => []
                    ]
                ]
            ]);
    }

    function test_store_a_reservation()
    {
        $data = Reservation::factory()->make();

        $params = [
            'room_id'       => $data->room_id,
            'start_date'    => $data->start_date->format('Y-m-d H:i:s'),
            'end_date'      => $data->end_date->format('Y-m-d H:i:s'),
            'emergency'     => $data->emergency,
            'doctor_id'     => $data->doctor_id,
            'patient_name'  => $data->patient_name,
            'notes'         => $data->notes,
            'restroom'      => $data->restroom,
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
            'patient_name'  => $data->patient_name,
            'notes'         => $data->notes,
            'restroom'      => $data->restroom,
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
                ]
            ]);
    }

    function test_update_a_reservation()
    {
        $reservation = Reservation::factory()->create();
        $data = Reservation::factory()->make();

        $params = [
            'start_date'    => $data->start_date->format('Y-m-d H:i:s'),
            'end_date'      => $data->end_date->format('Y-m-d H:i:s'),
        ];

        $this->put('api/v1/reservations/'.$reservation->id, $params)
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id'    => $reservation->id
                ]
            ]);

        $this->assertDatabaseHas('reservations', [
            'id'            => $reservation->id,
            'start_date'    => $data->start_date,
            'end_date'      => $data->end_date,
        ]);
    }

    function test_validate_reservation_at_update()
    {
        $reservation = Reservation::factory()->create();

        $this->putJson('api/v1/reservations/'.$reservation->id)
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'start_date'    => ['El campo fecha de inicio es obligatorio.'],
                    'end_date'      => ['El campo fecha de término es obligatorio.'],
                ]
            ]);
    }

    function test_delete_a_reservation()
    {
        $reservation = Reservation::factory()->create();

        $this->delete('api/v1/reservations/'.$reservation->id)
            ->assertStatus(204);

        $this->assertSoftDeleted('reservations', [
            'id'    => $reservation->id,
        ]);
    }
}
