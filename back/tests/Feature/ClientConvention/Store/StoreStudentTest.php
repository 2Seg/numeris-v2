<?php

namespace Tests\Feature\ClientConvention\Store;

use Illuminate\Http\JsonResponse;
use Tests\TestCaseWithAuth;

class StoreStudentTest extends TestCaseWithAuth
{
    protected $username = 'student';

    /**
     * @group student
     */
    public function testStudentCreatingConvention()
    {
        $client_id = 1;

        $convention = ['name'  => 'Convention de test'];
        $rate1 = [
            'name'          => 'Heures de test',
            'is_flat'       => false,
            'for_student'   => 10,
            'for_staff'     => 12,
            'for_client'    => 15,
        ];
        $rate2 = [
            'name'          => 'Forfait de test',
            'is_flat'       => true,
            'hours'         => 10,
            'for_student'   => 100,
            'for_staff'     => 120,
            'for_client'    => 150,
        ];

        $data = array_merge($convention, ['rates' => [$rate1, $rate2]]);

        $this->assertDatabaseMissing('conventions', $convention);
        $this->assertDatabaseMissing('rates', $rate1);
        $this->assertDatabaseMissing('rates', $rate2);

        $this->json('POST', route('clients.conventions.store', ['client_id' => $client_id]), $data)
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN)
            ->assertJson(['errors' => [trans('api.403')]]);

        $this->assertDatabaseMissing('conventions', $convention);
        $this->assertDatabaseMissing('rates', $rate1);
        $this->assertDatabaseMissing('rates', $rate2);
    }
}
