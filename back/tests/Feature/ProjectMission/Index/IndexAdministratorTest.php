<?php

namespace Tests\Feature\ProjectMission\Index;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Tests\TestCaseWithAuth;

class IndexAdministratorTest extends TestCaseWithAuth
{
    protected $username = Role::ADMINISTRATOR;

    /**
     * @group administrator
     */
    public function testAdministratorAccessingProjectMissionxIndex()
    {
        $project = $this->clientAndProjectAndMissionAndConventionWithBillsProvider()['project'];

        $this->json('GET', route('projects.missions.index', ['project_id' => $project->id]))
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure([
                'data' => [[
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
                    'project',
                ]],
                'links',
                'meta',
            ]);
    }
}
