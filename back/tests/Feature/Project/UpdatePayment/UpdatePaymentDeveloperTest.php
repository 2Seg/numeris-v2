<?php

namespace Tests\Feature\Project\UpdatePayment;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Tests\TestCaseWithAuth;

class UpdatePaymentDeveloperTest extends TestCaseWithAuth
{
    protected $username = Role::DEVELOPER;

    /**
     * @group developer
     */
    public function testDeveloperUpdatingProjectPayment()
    {
        $project = $this->projectProvider();

        $data = [
            'money_received_at' => '2018-01-01 00:00:00'
        ];

        $this->json('PATCH', route('projects.update.payment', ['project_id' => $project->id]), $data)
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
    }

    /**
     * @group developer
     */
    public function testDeveloperUpdatingUnknownProjectPayment()
    {
        $project_id = 0;

        $data = [
            'money_received_at' => '2018-01-01 00:00:00'
        ];

        $this->json('PATCH', route('projects.update.payment', ['project_id' => $project_id]), $data)
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND)
            ->assertJson(['errors' => [trans('errors.404')]]);
    }
}
