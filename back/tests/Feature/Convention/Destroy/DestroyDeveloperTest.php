<?php

namespace Tests\Feature\Convention\Destroy;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Tests\TestCaseWithAuth;

class DestroyDeveloperTest extends TestCaseWithAuth
{
    protected $username = Role::DEVELOPER;

    /**
     * @group developer
     */
    public function testDeveloperDeletingConvention()
    {
        $convention = $this->conventionProvider();

        $this->assertDatabaseHas('conventions', $convention->toArray());

        $this->json('DELETE', route('conventions.destroy', ['convention_id' => $convention->id]))
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('conventions', $convention->toArray());
    }

    /**
     * @group developer
     */
    public function testDeveloperDeletingConventionWithAssociatedProject()
    {
        $convention = $this->conventionAndProjectProvider()['convention'];

        $this->assertDatabaseHas('conventions', $convention->toArray());

        $this->json('DELETE', route('conventions.destroy', ['convention_id' => $convention->id]))
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN)
            ->assertJson(['errors' => [trans('errors.convention_has_project')]]);

        $this->assertDatabaseHas('conventions', $convention->toArray());
    }

    /**
     * @group developer
     */
    public function testDeveloperDeletingUnknownConvention()
    {
        $convention_id = 0; // Unknown convention

        $this->json('DELETE', route('conventions.destroy', ['convention_id' => $convention_id]))
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND)
            ->assertJson(['errors' => [trans('errors.404')]]);
    }
}
