<?php

use App\Role;
use App\User;
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
        //
        $admin = User::create([
            "name" => "admin user",
            "email" => "admin@readerscorner.co",
            "password" => bcrypt(123456),
        ]);
         $admin2 = User::create([
            'name' => 'hamdy',
            'email' => 'hamdy@readerscorner.co',
            "password" => bcrypt("hamdy1611"),
            
            ]
        );
        $posUsers = [
            [
             "name" => "ahmed adel",
             "email" => "ahmedadel@readerscorner.co",
             "employee_id" => 1,
             "password" => bcrypt("point90123456"),
            ],
            [
             "name" => "ahmed khaled",
             "email" => "ahmedkhaled@readerscorner.co",
             "employee_id" => 2,
             "password" => bcrypt("point90123456"),
            ]
            

        ];
        $posRole = Role::where("slug" , "pos")->first()->id;
        
        foreach ($posUsers as $user) {
            $pos = User::create([
            "name" =>$user['name'],
            "email" =>$user['email'],
            "employee_id" =>$user['employee_id'],
            "password" =>$user['password'],
            ]);

            $pos->roles()->attach($posRole);
        }
            
        $accounting = User::create([
            "name" => "Accounting user",
            "email" => "accounting@readerscorner.co",
            "password" => bcrypt(123456),
        ]);

        $adminRole =Role::where("slug" , "admin")->first()->id;
        $accountingRole = Role::where("slug" , "accounting")->first()->id;
        $admin->roles()->attach($adminRole);
        $admin2->roles()->attach($adminRole);
        $accounting->roles()->attach($accountingRole);
        $accounting->save();
        $admin->save();
        $admin2->save();
        $pos->save();
    }
}
