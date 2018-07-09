<?php

namespace Tests\Feature\Client\Delete;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Tests\TestCaseWithAuth;

class DeleteAdministratorTest extends TestCaseWithAuth
{
    protected $username = 'administrator';

    /**
     * @group administrator
     */
    public function testAdministratorDeletingClient()
    {
        $client_id = 1;
        $client = Client::find($client_id);
        $address = $client->address;

        $this->assertDatabaseHas('clients', $client->toArray());
        $this->assertDatabaseHas('addresses', $address->toArray());
        $this->assertNotEmpty(Project::where('client_id', $client_id)->get());

        $this->json('DELETE', route('clients.destroy', ['client_id' => $client_id]))
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('clients', $client->toArray());
        $this->assertDatabaseMissing('addresses', $address->toArray());
        $this->assertEmpty(Project::where('client_id', $client_id)->get());
    }
}
