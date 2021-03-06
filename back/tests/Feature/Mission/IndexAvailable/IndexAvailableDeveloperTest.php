<?php

namespace Tests\Feature\Mission\IndexAvailable;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Tests\TestCaseWithAuth;

class IndexAvailableDeveloperTest extends TestCaseWithAuth
{
    protected $username = Role::DEVELOPER;

    /**
     * @group developer
     */
    public function testDeveloperAccessingMissionIndex()
    {
        $this->json('GET', route('missions.index.available'))
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure([[
                'id',
                'isLocked',
                'reference',
                'title',
                'description',
                'startAt',
                'duration',
                'capacity',
                'applicationsCount',
                'waitingApplicationsCount',
                'acceptedApplicationsCount',
                'refusedApplicationsCount',
                'project' => ['client'],
            ]]);
    }
}
