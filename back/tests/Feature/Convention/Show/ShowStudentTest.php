<?php

namespace Tests\Feature\Convention\Show;

use Illuminate\Http\JsonResponse;
use Tests\TestCaseWithAuth;

class ShowStudentTest extends TestCaseWithAuth
{
    protected $username = 'student';

    /**
     * @group student
     *
     * @dataProvider conventionProvider
     */
    public function testStudentAccessingConventionShow($convention)
    {
        $this->json('GET', route('conventions.show', ['convention_id' => $convention->id]))
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN)
            ->assertJson(['errors' => [trans('api.403')]]);
    }
}
