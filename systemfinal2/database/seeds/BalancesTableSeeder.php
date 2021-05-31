<?php

use App\Balance;
use App\Branch;
use Illuminate\Database\Seeder;

class BalancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branches = Branch::all();
        foreach ($branches as $branch) {
        	Balance::create([
        		"balance" => 0,
        		"branch_id" => $branch->id
        	]);
        }
    }
}
