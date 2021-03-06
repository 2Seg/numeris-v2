<?php

namespace Tests\Feature\Project\Store;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Tests\TestCaseWithAuth;

class StoreDeveloperTest extends TestCaseWithAuth
{
    protected $username = Role::DEVELOPER;

    /**
     * @group developer
     */
    public function testDeveloperCreatingProject()
    {
        $convention = $this->conventionProvider();

        $data = [
            'client_id'     => $convention->client->id,
            'convention_id' => $convention->id,
            'name'          => 'Projet de test',
            'start_at'      => now()->toDateString(),
            'is_private'    => false,
        ];

        $this->assertDatabaseMissing('projects', $data);

        $this->json('POST', route('projects.store'), $data)
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonStructure([
                'id',
                'name',
                'step',
                'startAt',
                'isPrivate',
                'moneyReceivedAt',
                'createdAt',
                'updatedAt',
                'missionsCount',
                'usersCount',
            ]);

        $this->assertDatabaseHas('projects', $data);
    }

    /**
     * @group developer
     */
    public function testDeveloperCreatingProjectWithAlreadyUsedData()
    {
        $test_data = $this->clientAndProjectAndMissionAndConventionWithBillsProvider();
        $project = $test_data['project'];

        $data = [
            'client_id'     => $project->client->id,
            'convention_id' => $project->convention->id,
            'name'          => $project->name,
            'start_at'      => now()->toDateString(),
            'is_private'    => false,
        ];

        $this->assertDatabaseMissing('projects', $data);

        $this->json('POST', route('projects.store'), $data)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name']);

        $this->assertDatabaseMissing('projects', $data);
    }

    /**
     * @group developer
     */
    public function testDeveloperCreatingProjectWithUnknownProjectData()
    {
        $data = [
            'client_id'     => 0,
            'convention_id' => 0,
            'name'          => 'Projet de test',
            'start_at'      => now()->toDateString(),
            'is_private'    => false,
        ];

        $this->assertDatabaseMissing('projects', $data);

        $this->json('POST', route('projects.store'), $data)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['client_id', 'convention_id']);

        $this->assertDatabaseMissing('projects', $data);
    }

    /**
     * @group developer
     */
    public function testDeveloperCreatingProjectWithoutData()
    {
        $this->json('POST', route('projects.store'))
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['client_id', 'name', 'start_at']);
    }
}
