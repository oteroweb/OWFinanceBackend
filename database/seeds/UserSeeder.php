<?php

use Illuminate\Database\Seeder;
use App\Http\Models\Entities\User;
use App\Http\Models\Entities\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user = new User();
        $user->name = 'Jose Otero';
        $user->email = 'oteroweb.com@gmail.com';
        $user->password = bcrypt('saratoga1990');
        //$user->phone = '+5804125157489';
        $user->save();

        $user = new User();
        $user->name = 'Aura Griman';
        $user->email = 'argriman@gmail.com';
        $user->password = bcrypt('Rosa4107');
       // $user->phone = '123456';
        $user->save();

    }
}
