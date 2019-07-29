<?php

namespace Tests\Traits;

use App\Models\Application;
use App\Models\Mission;
use App\Models\User;

trait ApplicationProviderTrait
{
    public function ownApplicationProvider(): Application
    {
        $user = User::where('email', $this->username . '@isep.fr')->first();

        return factory(Application::class)->create(['user_id' => $user->id]);
    }

    public function otherUserApplicationProvider(): Application
    {
        $user = User::where('email', $this->username . '@isep.fr')->first();

        return factory(Application::class)->create([
            'user_id' => User::where('id', '!=', $user->id)
                ->get()->random(1)->get(0)->id
        ]);
    }

    public function applicationWithAvailableMissionProvider(): Application
    {
        $user = User::where('email', $this->username . '@isep.fr')->first();
        $mission = factory(Mission::class)->state('available')->create();

        return factory(Application::class)->create([
            'user_id'       => $user->id,
            'mission_id'    => $mission->id,
        ]);
    }
}
