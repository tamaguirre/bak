<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    function test_list_users()
    {
        $user = User::factory()->create();

        $this->get('/api/v1/users')
            ->assertStatus(200)
            ->assertJson([
                'data'  => [
                    0 => [
                        'id'    => $user->id,
                    ]
                ]
            ]);

    }

    function test_store_a_user()
    {
        $data = User::factory()->make();

        $params = [
            'name'      => $data->name,
            'email'     => $data->email,
            'password'  => 'password',
        ];

        $this->post('/api/v1/users', $params)
            ->assertStatus(201)
            ->assertJson([
                'data'  => [
                    'name'  => $data->name,
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'name'  => $data->name,
            'email' => $data->email,
        ]);
    }

    function test_validate_user_at_store()
    {
        $this->postJson('/api/v1/users')
            ->assertStatus(422)
            ->assertJson([
                'errors'  => [
                    'name'      => ['El campo nombre es obligatorio.'],
                    'email'     => ['El campo e-mail es obligatorio.'],
                    'password'  => ['El campo contraseña es obligatorio.'],
                ]
            ]);
    }

    function test_update_a_user()
    {
        $user = User::factory()->create();
        $data = User::factory()->make();

        $params = [
            'name'      => $data->name,
            'email'     => $data->email,
            'password'  => 'password',
        ];

        $this->put('/api/v1/users/'.$user->id, $params)
            ->assertStatus(200)
            ->assertJson([
                'data'  => [
                    'id'    => $user->id,
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'id'    => $user->id,
            'name'  => $data->name,
            'email' => $data->email,
        ]);
    }

    function test_validate_user_at_update()
    {
        $user = User::factory()->create();

        $this->putJson('/api/v1/users/'.$user->id)
            ->assertStatus(422)
            ->assertJson([
                'errors'  => [
                    'name'      => ['El campo nombre es obligatorio.'],
                    'email'     => ['El campo e-mail es obligatorio.'],
                    'password'  => ['El campo contraseña es obligatorio.'],
                ]
            ]);
    }

    function test_destroy_a_user()
    {
        $user = User::factory()->create();

        $this->delete('/api/v1/users/'.$user->id)
            ->assertStatus(204);

        $this->assertSoftDeleted('users', [
            'id'    => $user->id,
        ]);
    }
}
