<?php

namespace Tests\Feature\Convention\Show;

use Illuminate\Http\JsonResponse;
use Tests\TestCaseWithAuth;

class ShowDeveloperTest extends TestCaseWithAuth
{
    protected $username = 'developer';

    /**
     * @group developer
     *
     * @dataProvider conventionProvider
     */
    public function testDeveloperAccessingConventionShow($convention)
    {
        $this->json('GET', route('conventions.show', ['convention_id' => $convention->id]))
            ->assertStatus(JsonResponse::HTTP_OK)
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
                ]]
            ]);
    }

    /**
     * @group developer
     */
    public function testDeveloperAccessingConventionShowWithUnknownConvention()
    {
        $convention_id = 0; // Unknown convention

        $this->json('GET', route('conventions.show', ['convention_id' => $convention_id]))
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND)
            ->assertJson(['errors' => [trans('api.404')]]);
    }
}