<?php

namespace Tests\Traits;

use App\Models\Application;
use App\Models\User;

trait ApplicationProviderTrait
{
    public function ownApplicationProvider()
    {
        $this->refreshApplication();

        $user = User::where('username', $this->username)->first();

        return [[
            factory(Application::class)->create(
                ['user_id' => $user->id]
            )
        ]];
    }

    public function otherUserApplicationProvider()
    {
        $this->refreshApplication();

        $user = User::where('username', $this->username)->first();

        return [[
            factory(Application::class)->create([
                'user_id' => User::where('id', '!=', $user->id)
                    ->get()->random(1)->get(0)->id
            ])
        ]];
    }
}
