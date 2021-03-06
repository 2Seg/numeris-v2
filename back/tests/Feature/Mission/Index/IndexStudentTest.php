<?php

namespace Tests\Feature\Mission\Index;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Tests\TestCaseWithAuth;

class IndexStudentTest extends TestCaseWithAuth
{
    protected $username = Role::STUDENT;

    /**
     * @group student
     */
    public function testStudentAccesingMissionIndex()
    {
        $this->json('GET', route('missions.index'))
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN)
            ->assertJson(['errors' => [trans('errors.403')]]);
    }
}
