<?php

namespace Tests\Feature\MissionApplication\Store;
use App\Models\Application;
use App\Models\Mission;
use Illuminate\Http\JsonResponse;
use Tests\TestCaseWithAuth;

class StoreDeveloperTest extends TestCaseWithAuth
{
    protected $username = 'developer';

    /**
     * @group developer
     */
    public function testDeveloperCreatingApplication()
    {
        $user_id = 2;
        $mission_id = factory(Mission::class)->create()->id;

        $application = [
            'user_id'       => $user_id,
            'mission_id'    => $mission_id,
            'type'          => Application::STAFF_PLACEMENT,
            'status'        => Application::ACCEPTED
        ];
        $data = [
            'user_id' => $user_id,
        ];

        $this->assertDatabaseMissing('applications', $application);

        $this->json('POST', route('missions.applications.store', ['mission_id' => $mission_id]), $data)
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonStructure([
                'id',
                'userId',
                'missionId',
                'type',
                'status',
                'createdAt',
                'updatedAt',
            ]);

        $this->assertDatabaseHas('applications', $application);
    }

    /**
     * @group developer
     */
    public function testDeveloperCreatingApplicationWithUnknownMission()
    {
        $user_id = 0;
        $mission_id = 1;

        $application = [
            'user_id'       => $user_id,
            'mission_id'    => $mission_id,
            'type'          => Application::STAFF_PLACEMENT,
            'status'        => Application::ACCEPTED,
        ];
        $data = [
            'user_id' => $user_id,
        ];

        $this->assertDatabaseMissing('applications', $application);

        $this->json('POST', route('missions.applications.store', ['mission_id' => $mission_id]), $data)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['user_id']);

        $this->assertDatabaseMissing('applications', $application);

    }

    /**
     * @group developer
     */
    public function testDeveloperCreatingApplicationWithoutData()
    {
        $mission_id = 1;

        $this->json('POST', route('missions.applications.store', ['mission_id' => $mission_id]))
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND)
            ->assertJson(['errors' => [trans('api.404')]]);
    }

    /**
     * @group developer
     */
    public function testDeveloperApplyingUserToAlreadyAppliedMission()
    {
        $user_id = 2;
        $mission_id = 1;

        $data = [
            'user_id' => $user_id,
        ];

        $this->json('POST', route('missions.applications.store', ['mission_id' => $mission_id]), $data)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['user_id']);
    }

    /**
     * @group developer
     */
    public function testDeveloperApplyingUserToLockedMission()
    {
        $user_id = 2;
        $mission_id = $mission_id = factory(Mission::class)->state('locked')->create()->id;;

        $application = [
            'user_id'       => $user_id,
            'mission_id'    => $mission_id,
            'type'          => Application::USER_APPLICATION,
            'status'        => Application::WAITING
        ];
        $data = [
            'mission_id' => $mission_id,
        ];

        $this->assertDatabaseMissing('applications', $application);

        $this->json('POST', route('users.applications.store', ['user_id' => $user_id]), $data)
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN)
            ->assertJson([
                'error' => trans('api.403'),
            ]);

        $this->assertDatabaseMissing('applications', $application);
    }

    /**
     * @group developer
     */
    public function testDeveloperApplyingUserToPastMission()
    {
        $user_id = 2;
        $mission_id = $mission_id = factory(Mission::class)->state('past')->create()->id;;

        $application = [
            'user_id'       => $user_id,
            'mission_id'    => $mission_id,
            'type'          => Application::USER_APPLICATION,
            'status'        => Application::WAITING
        ];
        $data = [
            'mission_id' => $mission_id,
        ];

        $this->assertDatabaseMissing('applications', $application);

        $this->json('POST', route('users.applications.store', ['user_id' => $user_id]), $data)
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN)
            ->assertJson([
                'error' => trans('api.403'),
            ]);

        $this->assertDatabaseMissing('applications', $application);
    }
}
