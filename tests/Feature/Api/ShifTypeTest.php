<?php

namespace Tests\Feature\Api;

use App\Models\ShiftType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShifTypeTest extends TestCase
{
    use RefreshDatabase;

    function test_list_shift_types()
    {
        $shiftType = ShiftType::factory()->create();

        $this->get('api/v1/shift-types')
            ->assertStatus(200)
            ->assertJson([
                'data'  => [
                    0   => [
                        'id'    => $shiftType->id
                    ]
                ]
            ]);
    }
}
