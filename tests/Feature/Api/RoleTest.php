<?php

namespace Tests\Feature\Api;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    function test_list_roles()
    {
        $role = Role::factory()->create();

        $this->get('api/v1/roles')
            ->assertStatus(200)
            ->assertJson([
                'data'  => [
                    0   => [
                        'id'    => $role->id
                    ]
                ]
            ]);
    }
}
