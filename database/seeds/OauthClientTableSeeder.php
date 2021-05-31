<?php

use Illuminate\Database\Seeder;

class OauthClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oauth_clients')->insert([
		    [
		    	'name' => 'Laravel Personal Access Client',
		    	'secret' => 'GKdv4TNkSmgfaW100MDOM375lSVezeQCkx13s8XV' ,
		    	"redirect" => "http://localhost",
		    	"personal_access_client" => 1,
		    	"password_client" => 0 ,
		    	"revoked" => 0
		    ],
		    [
		    	'name' => 'Laravel Password Grant Client',
		    	'secret' => '3oGpd1hPg34DWj1D9ARmWXCV1h7Ml7YL0O7oO4GK' ,
		    	"redirect" => "http://localhost",
		    	"personal_access_client" => 0,
		    	"password_client" => 1 ,
		    	"revoked" => 0
		    ]
		]);
    }
}
