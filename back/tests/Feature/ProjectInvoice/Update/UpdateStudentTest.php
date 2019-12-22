<?php

namespace Tests\Feature\ProjectInvoice\Update;

use App\Models\Invoice;
use App\Models\Payslip;
use App\Models\Role;
use App\Models\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tests\TestCaseWithAuth;
use Tests\Traits\ClientProviderTrait;
use Tests\Traits\ProjectProviderTrait;
use Tests\Traits\UserProviderTrait;

class UpdateStudentTest extends TestCaseWithAuth
{
    protected $username = Role::STUDENT;

    /**
     * @group staff
     */
    public function testStudentUpdatingProjectInvoice()
    {
        $project = $this->clientAndProjectAndMissionAndConventionWithBillsProvider()['project'];
        $data = ['time_limit' => 30];

        $this->json('PUT', route('projects.invoices.update', ['project' => $project->id]), $data)
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN)
            ->assertJson(['errors' => [trans('api.403')]]);
    }
}
