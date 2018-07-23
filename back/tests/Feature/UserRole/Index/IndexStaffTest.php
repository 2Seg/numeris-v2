<?php

namespace Tests\Feature\UserRole\Index;

use Illuminate\Http\JsonResponse;
use Tests\TestCaseWithAuth;

class IndexStaffTest extends TestCaseWithAuth
{
    protected $username = 'staff';

    /**
     * @group staff
     */
    public function testStaffAccessingRoleIndex()
    {
        $user_id = 1;

        $this->json('GET', route('users.roles.index', ['user_id' => $user_id]))
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure([[
                'id',
                'name',
                'created_at'
            ]]);
    }
}