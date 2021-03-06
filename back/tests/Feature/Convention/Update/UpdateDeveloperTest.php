<?php

namespace Tests\Feature\Convention\Update;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Tests\TestCaseWithAuth;

class UpdateDeveloperTest extends TestCaseWithAuth
{
    protected $username = Role::DEVELOPER;

    /**
     * @group developer
     */
    public function testDeveloperUpdatingConventionAndCreatingRates()
    {
        $convention = $this->conventionProvider();

        $newRate = [
            'id'            => null,
            'name'          => 'Nouvelles heures de test',
            'is_flat'       => false,
            'for_student'   => 12,
            'for_staff'     => 122,
            'for_client'    => 152,
        ];

        $data = ['rates' => [$newRate]];
        unset($newRate['id']);

        $this->assertDatabaseMissing('rates', $newRate);

        $this->json('PUT', route('conventions.update', ['convention_id' => $convention->id]), $data)
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonStructure([
                'id',
                'name',
                'createdAt',
                'updatedAt',
                'rates' => [[
                    'id',
                    'name',
                    'isFlat',
                    'forStudent',
                    'forStaff',
                    'forClient',
                ]],
            ]);

        $this->assertDatabaseHas('rates', $newRate);
    }

    /**
     * @group developer
     */
    public function testDeveloperUpdatingConventionAndUpdatingRates()
    {
        $convention = $this->conventionProvider();

        $rate1 = [
            'id'            => $convention->rates->get(0)->id,
            'name'          => 'Heures de test',
            'is_flat'       => false,
            'for_student'   => 11,
            'for_staff'     => 121,
            'for_client'    => 151,
        ];
        $rate2 = [
            'id'            => $convention->rates->get(1)->id,
            'name'          => 'Forfait de test',
            'is_flat'       => true,
            'hours'         => 11,
            'for_student'   => 101,
            'for_staff'     => 121,
            'for_client'    => 151,
        ];

        $this->assertDatabaseMissing('rates', $rate1);
        $this->assertDatabaseMissing('rates', $rate2);

        $data = ['rates' => [$rate1, $rate2]];

        $this->json('PUT', route('conventions.update', ['convention_id' => $convention->id]), $data)
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonStructure([
                'id',
                'name',
                'createdAt',
                'updatedAt',
                'rates' => [[
                    'id',
                    'name',
                    'isFlat',
                    'forStudent',
                    'forStaff',
                    'forClient',
                ]],
            ]);

        $this->assertDatabaseHas('rates', $rate1);
        $this->assertDatabaseHas('rates', $rate2);
    }

    /**
     * @group developer
     */
    public function testDeveloperUpdatingConventionAndDeletingRate()
    {
        $convention = $this->conventionProvider();

        $rate1 = [
            'id'            => $convention->rates->get(0)->id,
            'name'          => 'Heures de test',
            'is_flat'       => false,
            'for_student'   => 11,
            'for_staff'     => 121,
            'for_client'    => 151,
        ];

        $data = ['rates' => [$rate1]];
        unset($rate1['id']);

        $this->assertDatabaseMissing('rates', $rate1);
        $this->assertDatabaseHas('rates', $convention->rates->get(1)->toArray());

        $this->json('PUT', route('conventions.update', ['convention_id' => $convention->id]), $data)
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonStructure([
                'id',
                'name',
                'createdAt',
                'updatedAt',
                'rates' => [[
                    'id',
                    'name',
                    'isFlat',
                    'forStudent',
                    'forStaff',
                    'forClient',
                ]],
            ]);

        $this->assertDatabaseHas('rates', $rate1);
        $this->assertDatabaseMissing('rates', $convention->rates->get(1)->toArray());
    }

    /**
     * @group developer
     */
    public function testDeveloperUpdatingConventionWithRatesWithBillsAndUpdatingRates()
    {
        $convention = $this->clientAndProjectAndMissionAndConventionWithBillsProvider()['convention'];

        $rate1 = [
            'id'            => $convention->rates->get(0)->id,
            'name'          => 'Heures de test',
            'is_flat'       => false,
            'for_student'   => 11,
            'for_staff'     => 121,
            'for_client'    => 151,
        ];
        $rate2 = [
            'id'            => $convention->rates->get(1)->id,
            'name'          => 'Forfait de test',
            'is_flat'       => true,
            'hours'         => 11,
            'for_student'   => 101,
            'for_staff'     => 121,
            'for_client'    => 151,
        ];

        $data = ['rates' => [$rate1, $rate2]];
        unset($rate1['id']);
        unset($rate2['id']);

        $this->assertDatabaseMissing('rates', $rate1);
        $this->assertDatabaseMissing('rates', $rate2);

        $this->json('PUT', route('conventions.update', ['convention_id' => $convention->id]), $data)
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN)
            ->assertJson(['errors' => [trans('errors.convention_has_project')]]);

        $this->assertDatabaseMissing('rates', $rate1);
        $this->assertDatabaseMissing('rates', $rate2);
    }

    /**
     * @group developer
     */
    public function testDeveloperUpdatingConventionWithRatesWithBillsAndDeletingRate()
    {
        $convention = $this->clientAndProjectAndMissionAndConventionWithBillsProvider()['convention'];

        $rate1 = [
            'id'            => $convention->rates->get(0)->id,
            'name'          => 'Heures de test',
            'is_flat'       => false,
            'for_student'   => 11,
            'for_staff'     => 121,
            'for_client'    => 151,
        ];

        $data = ['rates' => [$rate1]];
        unset($rate1['id']);

        $this->assertDatabaseMissing('rates', $rate1);
        $this->assertDatabaseHas('rates', $convention->rates->get(1)->toArray());

        $this->json('PUT', route('conventions.update', ['convention_id' => $convention->id]), $data)
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN)
            ->assertJson(['errors' => [trans('errors.convention_has_project')]]);

        $this->assertDatabaseMissing('rates', $rate1);
        $this->assertDatabaseHas('rates', $convention->rates->get(1)->toArray());
    }

    /**
     * @group developer
     */
    public function testDeveloperUpdatingUnknownConvention()
    {
        $convention_id = 0; // Unknown convention

        $rate1 = [
            'id'            => null,
            'name'          => 'Heures de test',
            'is_flat'       => false,
            'for_student'   => 11,
            'for_staff'     => 121,
            'for_client'    => 151,
        ];
        $rate2 = [
            'id'            => null,
            'name'          => 'Forfait de test',
            'is_flat'       => true,
            'hours'         => 11,
            'for_student'   => 101,
            'for_staff'     => 121,
            'for_client'    => 151,
        ];

        $data = ['rates' => [$rate1, $rate2]];

        $this->assertDatabaseMissing('rates', $rate1);
        $this->assertDatabaseMissing('rates', $rate2);

        $this->json('PUT', route('conventions.update', ['convention_id' => $convention_id]), $data)
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND)
            ->assertJson(['errors' => [trans('errors.404')]]);

        $this->assertDatabaseMissing('rates', $rate1);
        $this->assertDatabaseMissing('rates', $rate2);
    }

    /**
     * @group developer
     */
    public function testDeveloperUpdatingConventionWithoutData()
    {
        $convention = $this->conventionProvider();

        $this->json('PUT', route('conventions.update', ['convention_id' => $convention->id]))
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['rates']);
    }
}
