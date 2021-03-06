<?php

namespace Tests\Feature\ProjectUser\Store;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Tests\TestCaseWithAuth;

class DestroyDeveloperTest extends TestCaseWithAuth
{
    protected $username = Role::DEVELOPER;

    /**
     * @group developer
     */
    public function testDeveloperRemovingUserFromProject()
    {
        $test_data = $this->privateProjectAndUserInProjectProvider();
        $project = $test_data['project'];
        $user = $test_data['user'];

        $data = [
            'project_id'    => $project->id,
            'user_id'       => $user->id,
        ];

        $this->assertDatabaseHas('project_user', $data);

        $this->json('DELETE', route('projects.users.destroy', $data))
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('project_user', $data);
    }

    /**
     * @group developer
     */
    public function testDeveloperRemovingUserFromPublicProject()
    {
        $test_data = $this->publicProjectAndUserProvider();
        $project = $test_data['project'];
        $user = $test_data['user'];

        $data = [
            'project_id'    => $project->id,
            'user_id'       => $user->id,
        ];

        $this->assertDatabaseMissing('project_user', $data);

        $this->json('DELETE', route('projects.users.destroy', $data))
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND)
            ->assertJson(['errors' => [trans('errors.404')]]);

        $this->assertDatabaseMissing('project_user', $data);
    }

    /**
     * @group developer
     */
    public function testDeveloperRemovingUserNotInProjectFromProject()
    {
        $test_data = $this->privateProjectAndUserProvider();
        $project = $test_data['project'];
        $user = $test_data['user'];

        $data = [
            'project_id'    => $project->id,
            'user_id'       => $user->id,
        ];

        $this->assertDatabaseMissing('project_user', $data);

        $this->json('DELETE', route('projects.users.destroy', $data))
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN)
            ->assertJson(['errors' => [trans('errors.project_doesnot_contain_user')]]);

        $this->assertDatabaseMissing('project_user', $data);
    }

    /**
     * @group developer
     */
    public function testDeveloperRemovingDeletedUserFromProject()
    {
        $test_data = $this->privateProjectAndUserInProjectProvider();
        $project = $test_data['project'];
        $user = $test_data['user'];

        $user->update(['deleted_at' => now()]);

        $data = [
            'project_id'    => $project->id,
            'user_id'       => $user->id,
        ];

        $this->assertDatabaseHas('project_user', $data);

        $this->json('DELETE', route('projects.users.destroy', $data))
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN)
            ->assertJson(['errors' => [trans('errors.profile_deleted')]]);

        $this->assertDatabaseHas('project_user', $data);
    }
}
