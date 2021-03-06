<?php

namespace Tests\Feature\User\Update;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Tests\TestCaseWithAuth;

class UpdateDeveloperTest extends TestCaseWithAuth
{
    protected $username = Role::DEVELOPER;

    /**
     * @group developer
     */
    public function testDeveloperUpdatingStudentWithAllFields()
    {
        $user = $this->activeStudentProvider();

        $user_data = $db_data = [
            'email'                     => 'test@isep.fr',
            'first_name'                => 'Test',
            'last_name'                 => 'Numeris',
            'promotion'                 => now()->addYear()->year,
            'phone'                     => '0123456789',
            'nationality'               => 'Française',
            'birth_date'                => '2001-06-13 09:50:16',
            'birth_city'                => 'Paris',
            'social_insurance_number'   => '118357343809929',
            'iban'                      => 'QUOIUREESTTEN93',
            'bic'                       => 'ZVCRVZJG'
        ];
        $address_data = [
            'street'    => '1 rue Quelquepart',
            'zip_code'  => '75015',
            'city'      => 'Paris'
        ];

        // Add 'password' datas after init to avoid the check on unknown column
        // 'password_confirmation' and on uncrypted 'password'
        $user_data['password'] = $user_data['password_confirmation'] = 'azertyuiop';

        $data = array_merge($user_data, ['address' => $address_data]);

        $this->assertDatabaseMissing('users', $db_data);
        $this->assertDatabaseMissing('addresses', $address_data);

        $this->json('PUT', route('users.update', ['user_id' => $user->id]), $data)
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonStructure([
                'id',
                'preferenceId',
                'addressId',
                'activated',
                'touAccepted',
                'emailVerifiedAt',
                'subscriptionPaidAt',
                'email',
                'firstName',
                'lastName',
                'promotion',
                'phone',
                'nationality',
                'birthDate',
                'birthCity',
                'socialInsuranceNumber',
                'iban',
                'bic',
                'createdAt',
                'updatedAt',
            ]);

        $this->assertDatabaseHas('users', $db_data);
        $this->assertDatabaseHas('addresses', $address_data);
    }

    /**
     * @group developer
     */
    public function testDeveloperUpdatingStaffWithAllFields()
    {
        $user = $this->activeStaffProvider();

        $user_data = $db_data = [
            'email'                     => 'test@isep.fr',
            'first_name'                => 'Test',
            'last_name'                 => 'Numeris',
            'promotion'                 => now()->addYear()->year,
            'phone'                     => '0123456789',
            'nationality'               => 'Française',
            'birth_date'                => '2001-06-13 09:50:16',
            'birth_city'                => 'Paris',
            'social_insurance_number'   => '118357343809929',
            'iban'                      => 'QUOIUREESTTEN93',
            'bic'                       => 'ZVCRVZJG'
        ];
        $address_data = [
            'street'    => '1 rue Quelquepart',
            'zip_code'  => '75015',
            'city'      => 'Paris'
        ];

        // Add 'password' datas after init to avoid the check on unknown column
        // 'password_confirmation' and on uncrypted 'password'
        $user_data['password'] = $user_data['password_confirmation'] = 'azertyuiop';

        $data = array_merge($user_data, ['address' => $address_data]);

        $this->assertDatabaseMissing('users', $db_data);
        $this->assertDatabaseMissing('addresses', $address_data);

        $this->json('PUT', route('users.update', ['user_id' => $user->id]), $data)
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonStructure([
                'id',
                'preferenceId',
                'addressId',
                'activated',
                'touAccepted',
                'subscriptionPaidAt',
                'email',
                'firstName',
                'lastName',
                'promotion',
                'phone',
                'nationality',
                'birthDate',
                'birthCity',
                'socialInsuranceNumber',
                'iban',
                'bic',
                'createdAt',
                'updatedAt',
            ]);

        $this->assertDatabaseHas('users', $db_data);
        $this->assertDatabaseHas('addresses', $address_data);
    }

    /**
     * @group developer
     */
    public function testDeveloperUpdatingAdministratorWithAllFields()
    {
        $user = $this->activeAdministratorProvider();

        $user_data = $db_data = [
            'email'                     => 'test@isep.fr',
            'first_name'                => 'Test',
            'last_name'                 => 'Numeris',
            'promotion'                 => now()->addYear()->year,
            'phone'                     => '0123456789',
            'nationality'               => 'Française',
            'birth_date'                => '2001-06-13 09:50:16',
            'birth_city'                => 'Paris',
            'social_insurance_number'   => '118357343809929',
            'iban'                      => 'QUOIUREESTTEN93',
            'bic'                       => 'ZVCRVZJG'
        ];
        $address_data = [
            'street'    => '1 rue Quelquepart',
            'zip_code'  => '75015',
            'city'      => 'Paris'
        ];

        // Add 'password' datas after init to avoid the check on unknown column
        // 'password_confirmation' and on uncrypted 'password'
        $user_data['password'] = $user_data['password_confirmation'] = 'azertyuiop';

        $data = array_merge($user_data, ['address' => $address_data]);

        $this->assertDatabaseMissing('users', $db_data);
        $this->assertDatabaseMissing('addresses', $address_data);

        $this->json('PUT', route('users.update', ['user_id' => $user->id]), $data)
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonStructure([
                'id',
                'preferenceId',
                'addressId',
                'activated',
                'touAccepted',
                'subscriptionPaidAt',
                'email',
                'firstName',
                'lastName',
                'promotion',
                'phone',
                'nationality',
                'birthDate',
                'birthCity',
                'socialInsuranceNumber',
                'iban',
                'bic',
                'createdAt',
                'updatedAt',
            ]);

        $this->assertDatabaseHas('users', $db_data);
        $this->assertDatabaseHas('addresses', $address_data);
    }

    /**
     * @group developer
     */
    public function testDeveloperUpdatingDeveloperWithAllFields()
    {
        $user = $this->activeDeveloperProvider();

        $user_data = $db_data = [
            'email'                     => 'test@isep.fr',
            'first_name'                => 'Test',
            'last_name'                 => 'Numeris',
            'promotion'                 => now()->addYear()->year,
            'phone'                     => '0123456789',
            'nationality'               => 'Française',
            'birth_date'                => '2001-06-13 09:50:16',
            'birth_city'                => 'Paris',
            'social_insurance_number'   => '118357343809929',
            'iban'                      => 'QUOIUREESTTEN93',
            'bic'                       => 'ZVCRVZJG'
        ];
        $address_data = [
            'street'    => '1 rue Quelquepart',
            'zip_code'  => '75015',
            'city'      => 'Paris'
        ];

        // Add 'password' datas after init to avoid the check on unknown column
        // 'password_confirmation' and on uncrypted 'password'
        $user_data['password'] = $user_data['password_confirmation'] = 'azertyuiop';

        $data = array_merge($user_data, ['address' => $address_data]);

        $this->assertDatabaseMissing('users', $db_data);
        $this->assertDatabaseMissing('addresses', $address_data);

        $this->json('PUT', route('users.update', ['user_id' => $user->id]), $data)
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonStructure([
                'id',
                'preferenceId',
                'addressId',
                'activated',
                'touAccepted',
                'subscriptionPaidAt',
                'email',
                'firstName',
                'lastName',
                'promotion',
                'phone',
                'nationality',
                'birthDate',
                'birthCity',
                'socialInsuranceNumber',
                'iban',
                'bic',
                'createdAt',
                'updatedAt',
            ]);

        $this->assertDatabaseHas('users', $db_data);
        $this->assertDatabaseHas('addresses', $address_data);
    }

    /**
     * @group developer
     */
    public function testDeveloperUpdatingUserWithAlreadyUsedData()
    {
        $user = $this->activeUserProvider();

        $user_data = $db_data = [
            'email'                     => 'developer@isep.fr', // Already used
            'first_name'                => 'Test',
            'last_name'                 => 'Numeris',
            'promotion'                 => '1991',
            'phone'                     => '0123456789',
            'nationality'               => 'Française',
            'birth_date'                => '2001-06-13 09:50:16',
            'birth_city'                => 'Paris',
            'social_insurance_number'   => '118357343809929',
            'iban'                      => 'QUOIUREESTTEN93',
            'bic'                       => 'ZVCRVZJG'
        ];
        $address_data = [
            'street'    => '1 rue Quelquepart',
            'zip_code'  => '75015',
            'city'      => 'Paris'
        ];

        // Add 'password' datas after init to avoid the check on unknown column
        // 'password_confirmation' and on uncrypted 'password'
        $user_data['password'] = $user_data['password_confirmation'] = 'azertyuiop';

        $data = array_merge($user_data, ['address' => $address_data]);

        $this->assertDatabaseMissing('users', $db_data);
        $this->assertDatabaseMissing('addresses', $address_data);

        $this->json('PUT', route('users.update', ['user_id' => $user->id]), $data)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email']);

        $this->assertDatabaseMissing('users', $db_data);
        $this->assertDatabaseMissing('addresses', $address_data);
    }

    /**
     * @group developer
     */
    public function testDeveloperUpdatingUserWithOtherDomainEmail()
    {
        $user = $this->activeUserProvider();

        $user_data = $db_data = [
            'email'                     => 'developer@other-domain.fr', // Not @isep.fr
            'first_name'                => 'Test',
            'last_name'                 => 'Numeris',
            'promotion'                 => '1990', // < current year
            'phone'                     => '0123456789',
            'nationality'               => 'Française',
            'birth_date'                => '2001-06-13 09:50:16',
            'birth_city'                => 'Paris',
            'social_insurance_number'   => '118357343809929',
            'iban'                      => 'QUOIUREESTTEN93',
            'bic'                       => 'ZVCRVZJG'
        ];
        $address_data = [
            'street'    => '1 rue Quelquepart',
            'zip_code'  => '75015',
            'city'      => 'Paris'
        ];

        // Add 'password' datas after init to avoid the check on unknown column
        // 'password_confirmation' and on uncrypted 'password'
        $user_data['password'] = $user_data['password_confirmation'] = 'azertyuiop';

        $data = array_merge($user_data, ['address' => $address_data]);

        $this->assertDatabaseMissing('users', $db_data);
        $this->assertDatabaseMissing('addresses', $address_data);

        $this->json('PUT', route('users.update', ['user_id' => $user->id]), $data)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email', 'promotion']);

        $this->assertDatabaseMissing('users', $db_data);
        $this->assertDatabaseMissing('addresses', $address_data);
    }

    /**
     * @group developer
     */
    public function testDeveloperUpdatingUserWithoutData()
    {
        $user = $this->activeUserProvider();

        $this->json('PUT', route('users.update', ['user' => $user->id]))
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'first_name', 'last_name', 'email',
                'phone', 'nationality', 'birth_date', 'birth_city',
                'social_insurance_number', 'iban', 'bic',
                'address.street', 'address.zip_code', 'address.city',
            ]);
    }
}
