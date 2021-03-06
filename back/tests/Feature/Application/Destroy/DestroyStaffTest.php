<?php

namespace Tests\Feature\Application\Destroy;

use App\Mail\ApplicationRemovedMail;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Tests\TestCaseWithAuth;

class DestroyStaffTest extends TestCaseWithAuth
{
    protected $username = Role::STAFF;

    /**
     * @group staff
     */
    public function testStaffDeletingHisApplication()
    {
        $application = $this->ownApplicationProvider();

        $this->assertDatabaseHas('applications', $application->toArray());

        $this->json('DELETE', route('applications.destroy', ['application_id' => $application->id]))
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        Mail::assertSent(ApplicationRemovedMail::class);

        $this->assertDatabaseMissing('applications', $application->toArray());
    }

    /**
     * @group staff
     */
    public function testStaffDeletingApplicationOfOtherUser()
    {
        $application = $this->otherUserApplicationProvider();

        $this->assertDatabaseHas('applications', $application->toArray());

        $this->json('DELETE', route('applications.destroy', ['application_id' => $application->id]))
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN)
            ->assertJson(['errors' => [trans('errors.403')]]);

        Mail::assertNothingSent();

        $this->assertDatabaseHas('applications', $application->toArray());
    }
}
