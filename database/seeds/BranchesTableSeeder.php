<?php

use App\Balance;
use App\Branch;
use Illuminate\Database\Seeder;

class BranchesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $branches = [
        	[

        		"name" => "point 90",
				"email" => "point90@raederscorner.co",
				"address" => "point 90 mall",
				"phone" => "0123456789",
				"rent" => 60000,
				"bills" => 3000,
        		
        	],
        	[

        		"name" => "cairo fistival",
				"email" => "cairo_fistival@raederscorner.co",
				"address" => "cairo fistival mall",
				"phone" => "0123456789",
				"rent" => 50000,
				"bills" => 4000,
        		
        	]

        ];

        foreach ($branches as $branch) {
        	Branch::create([
        		"name" =>$branch['name'],
				"email" =>$branch['email'],
				"address" =>$branch['address'],
				"phone" =>$branch['phone'],
				"rent" =>$branch['rent'],
				"bills" =>$branch['bills'],
        	]);
        }
		for ($i=0; $i < 2 ; $i++){
        	Balance::create(['branch_id' => 1 , 'balance' => 0]);
        }
    }
}
