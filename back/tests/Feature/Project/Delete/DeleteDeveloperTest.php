<?php

namespace Tests\Feature\Project\Delete;

use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Tests\TestCaseWithAuth;

class DeleteDeveloperTest extends TestCaseWithAuth
{
    protected $username = 'developer';

    /**
     * @group developer
     */
    public function testDeveloperDeletingProject()
    {
        $project_id = 1;
        $project = Project::find($project_id);

        $this->assertDatabaseHas('projects', $project->toArray());
        // TODO: check missions

        $this->json('DELETE', route('projects.destroy', ['project_id' => $project_id]))
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('projects', $project->toArray());
        // TODO: check missions
    }

    /**
     * @group developer
     */
    public function testDeveloperDeletingUnknownProject()
    {
        $project_id = 0; // Unknown project

        $this->json('DELETE', route('projects.destroy', ['project_id' => $project_id]))
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND)
            ->assertJson([
                'error' => trans('api.404')
            ]);
    }
}
