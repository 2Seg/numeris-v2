<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Role::all()->isEmpty()) {
            $this->call(RolesTableSeeder::class);
        }

        // Generate 2 developer accounts
        if (! User::where('email', 'developer@numeris-isep.fr')->first()) {
            $this->createUser(1, 'developer', 'active', [
                'email'         => 'developer@numeris-isep.fr',
                'username'      => 'developer',
                'first_name'    => 'Developer',
                'last_name'     => 'Numeris',
            ]);
        }

<<<<<<< HEAD
        if (! User::where('email', 'developer2@numeris-isep.fr')->first()) {
            $this->createUser(1, 'developer', 'active', [
                'email'         => 'developer2@numeris-isep.fr',
                'username'      => 'developer2',
                'first_name'    => 'Developer2',
                'last_name'     => 'Numeris',
            ]);
        }

        // Generate 2 administrator accounts
=======
        // Generate administrator account
>>>>>>> 87eaff66f3af388a6fef382b7166e0a0a0a56c17
        if (! User::where('email', 'administrator@numeris-isep.fr')->first()) {
            $this->createUser(1, 'administrator', 'active', [
                'email'         => 'administrator@numeris-isep.fr',
                'username'      => 'administrator',
                'first_name'    => 'Administrator',
                'last_name'     => 'Numeris',
            ]);
        }

        if (! User::where('email', 'administrator2@numeris-isep.fr')->first()) {
            $this->createUser(1, 'administrator', 'active', [
                'email'         => 'administrator2@numeris-isep.fr',
                'username'      => 'administrator2',
                'first_name'    => 'Administrator2',
                'last_name'     => 'Numeris',
            ]);
        }

        // Generate 2 staff accounts
        if (! User::where('email', 'staff@numeris-isep.fr')->first()) {
            $this->createUser(1, 'staff', 'active', [
                'email'         => 'staff@numeris-isep.fr',
                'username'      => 'staff',
                'first_name'    => 'Staff',
                'last_name'     => 'Numeris',
            ]);
        }

        if (! User::where('email', 'staff2@numeris-isep.fr')->first()) {
            $this->createUser(1, 'staff', 'active', [
                'email'         => 'staff2@numeris-isep.fr',
                'username'      => 'staff2',
                'first_name'    => 'Staff2',
                'last_name'     => 'Numeris',
            ]);
        }

        // Generate student account
        if (! User::where('email', 'student@numeris-isep.fr')->first()) {
            $this->createUser(1, 'student', 'active', [
                'email'         => 'student@numeris-isep.fr',
                'username'      => 'student',
                'first_name'    => 'Student',
                'last_name'     => 'Numeris',
            ]);
        }

        if (! User::where('email', 'student2@numeris-isep.fr')->first()) {
            $this->createUser(1, 'student', 'active', [
                'email'         => 'student2@numeris-isep.fr',
                'username'      => 'student2',
                'first_name'    => 'Student2',
                'last_name'     => 'Numeris',
            ]);
        }

        // Generate my account ;)
        if (! User::where('email', 'eliott.de-seguier@numeris-isep.fr')->first()) {
            $this->createUser(1, 'developer', 'active', [
                'email'             => 'eliottdes@gmail.com',
                'username'          => '2Seg',
                'first_name'        => 'Eliott',
                'last_name'         => 'de Séguier',
                'student_number'    => 8740,
                'promotion'         => '2019',
                'phone'             => '06 63 67 06 80',
                'nationality'       => 'Française',
                'birth_date'        => '1995-06-29 00:00:00',
                'birth_city'        => 'Paris'
            ]);
        }

        // Generate 5 random active users
        $this->createUser(5, 'student', 'active');

        // Generate 5 random inactive users
        $this->createUser(5);

    }

    private function createUser($number = 1, $type = 'student', $state = 'inactive', $attributes = [])
    {
        if ($number <= 1) {
            return factory(User::class)->states($state)
                ->create($attributes)
                ->roles()
                ->attach(Role::where('name', $type)->first());
        } else {
            return factory(User::class, $number)->states($state)
                ->create($attributes)
                ->map(function ($user) use ($type) {
                   $user->roles()
                       ->attach(Role::where('name', $type)->first());
                });
        }
    }
}
