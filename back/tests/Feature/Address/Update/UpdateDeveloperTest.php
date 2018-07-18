<?php

namespace Tests\Feature\Address\Update;

use Illuminate\Http\JsonResponse;
use Tests\TestCaseWithAuth;

class UpdateDeveloperTest extends TestCaseWithAuth
{
    protected $username = 'developer';

    /**
     * @group developer
     */
    public function testDeveloperUpdatingAddress()
    {
        $address_id = 1;
        $data = [
            'street' => '69 rue Balard',
            'zip_code' => 75015,
            'city' => 'Paris',
        ];

        $this->assertDatabaseMissing('addresses', $data);

        $this->json('PUT', route('addresses.update', ['address_id' => $address_id]), $data)
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonStructure([
                'id',
                'street',
                'zip_code',
                'city',
            ]);

        $this->assertDatabaseHas('addresses', $data);
    }

    /**
     * @group developer
     */
    public function testDeveloperUpdatingAddressWithoutData()
    {
        $address_id = 1;
        $data = [];

        $this->json('PUT', route('addresses.update', ['address_id' => $address_id]), $data)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([ 'street', 'zip_code', 'city']);
    }
}
