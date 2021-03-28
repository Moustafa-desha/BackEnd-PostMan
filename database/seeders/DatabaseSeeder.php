<?php

namespace Database\Seeders;

use App\Models\ExamTypes;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $examTypes = ['True&False','choices','asiyes'];
            foreach ($examTypes as $examType)
            {
                ExamTypes::create([
                    'name'=>$examType
                ]);
            }


        $roles = ['Admin' , 'Teacher' , 'Student' , 'Support' , 'Secretary'];

        foreach ($roles as $role){
            Role::create([
                    'name' => $role,
            ]);
        }

        User::create([
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make(123456789),
                'phone' => '+79998068033',
                'role_id' => 1 ,
        ]);

        User::create([
            'name' => 'Teacher',
            'email' => 'teacher@gmail.com',
            'password' => Hash::make(123456789),
            'phone' => '+79998068033',
            'role_id' => 2 ,
        ]);

        User::create([
            'name' => 'Student',
            'email' => 'student@gmail.com',
            'password' => Hash::make(123456789),
            'phone' => '+79998068033',
            'role_id' => 3 ,
        ]);

        User::create([
            'name' => 'Support',
            'email' => 'support@gmail.com',
            'password' => Hash::make(123456789),
            'phone' => '+79998068033',
            'role_id' => 4 ,
        ]);

        User::create([
            'name' => 'Secertary',
            'email' => 'secertary@gmail.com',
            'password' => Hash::make(123456789),
            'phone' => '+79998068033',
            'role_id' => 5 ,
        ]);



    }
}
