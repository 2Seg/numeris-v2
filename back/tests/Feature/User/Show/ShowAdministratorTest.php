<?php

namespace Tests\Feature\User\Show;

use Illuminate\Http\JsonResponse;
use Tests\TestCaseWithAuth;

class ShowAdministratorTest extends TestCaseWithAuth
{
    protected $username = 'administrator';

    /**
     * @group administrator
     */
    public function testAdministratorAccessingUserShow()
    {
        $user_id = 1;

        $this->json('GET', route('users.show', ['user_id' => $user_id]))
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure([
                'id',
                'preference_id',
                'address_id',
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
                'updated_at',

                'address'       => [
                    'id',
                    'street',
                    'zip_code',
                    'city',
                ],
                'preference'    => [
                    'id',
                    'on_new_mission',
                    'on_acceptance',
                    'on_refusal',
                ],
                'role'          => [
                    'id',
                    'name',
                    'created_at',
                ],
            ]);
    }
}
