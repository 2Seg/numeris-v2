<?php

namespace Tests\Feature\User\Index;

use Illuminate\Http\JsonResponse;
use Tests\TestCaseWithAuth;

class IndexAdministratorTest extends TestCaseWithAuth
{
    protected $username = 'administrator';

    /**
     * @group administrator
     */
    public function testAdministratorAccesingUserIndex()
    {
        $this->json('GET', route('users.index'))
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure([[
                'id',
                'activated',
                'tou_accepted',
                'subscription_paid_at',
                'email',
                'username',
                'first_name',
                'last_name',
                'student_number',
                'promotion',
                'phone',
                'nationality',
                'birth_date',
                'birth_city',
                'social_insurance_number',
                'iban',
                'bic',
                'created_at',
                'updated_at'
            ]]);
    }
}