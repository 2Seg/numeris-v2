<?php

namespace Tests\Feature\Mission\Update;

use Illuminate\Http\JsonResponse;
use Tests\TestCaseWithAuth;

class UpdateDeveloperTest extends TestCaseWithAuth
{
    protected $username = 'developer';

    /**
     * @group developer
     */
    public function testDeveloperUpdatingMission()
    {
        $mission_id = 1;

        $mission_data = [
            'project_id'    => 1,
            'title'         => 'Mission de test',
            'description'   => 'Description de la mission de test',
            'start_at'      => '2018-01-01 08:00:00',
            'duration'      => 7,
            'capacity'      => 2,
        ];
        $address_data = [
            'street'    => '1 rue Quelquepart',
            'zip_code'  => '75015',
            'city'      => 'Paris'
        ];
        $data = array_merge($mission_data, $address_data);

        $this->assertDatabaseMissing('missions', $mission_data);
        $this->assertDatabaseMissing('addresses', $address_data);

        $this->json('PUT', route('missions.update', ['mission_id' => $mission_id]), $data)
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonStructure([
                'id',
                'isLocked',
                'title',
                'description',
                'startAt',
                'duration',
                'capacity',
            ]);

        $this->assertDatabaseHas('missions', $mission_data);
        $this->assertDatabaseHas('addresses', $address_data);
    }

    /**
     * @group developer
     */
    public function testDeveloperUpdatingUserWithoutData()
    {
        $mission_id = 1;

        $this->json('PUT', route('missions.update', ['mission_id' => $mission_id]))
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'project_id',
                'title',
                'description',
                'start_at',
                'duration',
                'capacity',
                'street',
                'zip_code',
                'city',
            ]);
    }
}
