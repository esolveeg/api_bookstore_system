<?php

use App\Employee;
use Illuminate\Database\Seeder;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employees = [
            [
            'name' => 'ahmed adel',
            'email' => 'ahmedadel@readerscorner.co',
            'phone' => '01022052546',
            'salary' => 2000,
            'target' => 50000,
            'branch_id' => 1,
            ],
            [
            'name' => 'ahmed khaled',
            'email' => 'ahmedkhaled@readerscorner.co',
            'phone' => '',
            'salary' => 2500,
            'target' => 80000,
            'branch_id' => 1,
            ],
            [
            'name' => 'yasmeen',
            'email' => 'yasmeen@readerscorner.co',
            'phone' => '',
            'salary' => 2500,
            'target' => 80000,
            'branch_id' => 1,
            ],
            [
            'name' => 'hamdy',
            'email' => 'hamdy@readerscorner.co',
            'phone' => '01022052546',
            'branch_id' => null,
            'target' => null,
            'salary' => 7000,
            ],
        ];
        foreach ($employees as $employee) {
           Employee::create([
            'name' =>$employee['name'],
            'email' =>$employee['email'],
            'phone' =>$employee['phone'],
            'salary' =>$employee['salary'],
            'target' =>$employee['target'],
            'branch_id' =>$employee['branch_id'],
           ]);
        }

        
    }
}
