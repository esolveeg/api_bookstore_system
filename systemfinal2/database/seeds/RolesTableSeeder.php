<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $RoleItems = [
            [
                'name'        => 'Admin',
                'slug'        => 'admin',
                'description' => 'Admin Role',
                'level'       => 5,
            ],
            [
                'name'        => 'P.O.S',
                'slug'        => 'pos',
                'description' => 'Point of sale role',
                'level'       => 5,
            ],
            [
                'name'        => 'Accounting',
                'slug'        => 'accounting',
                'description' => 'Acconting role',
                'level'       => 5,
            ],
            
            
        ];

        foreach ($RoleItems as $RoleItem) {
            $newRoleItem = Role::where('slug', '=', $RoleItem['slug'])->first();
            if ($newRoleItem === null) {
                $newRoleItem =Role::create([
                    'name'          => $RoleItem['name'],
                    'slug'          => $RoleItem['slug'],
                    'description'   => $RoleItem['description'],
                    'level'         => $RoleItem['level'],
                ]);
	            if($newRoleItem['slug'] == 'admin'){
	                $permissions = Permission::select('id')->get();
					$newRoleItem->permissions()->sync($permissions);
	            }
	            if($newRoleItem['slug'] == 'pos'){
	            	$slugs = [
                        'create_orders',
                        'edit_orders',
                        'view_orders',
                        'read_orders',
                        'view_transactions',
                        'approve_transactions',
                        'read_transactions',
                        'view_products',
                        'read_products',
                        'create_customers',
                        'view_customers',
                        'read_customers',
                        'edit_customers',
                        'pos_dashboard',
                    ];
	               $permissions = DB::table('permissions')
                    ->whereIn('slug', $slugs)
                    ->select('id')->get();
                    $ids = [];
				    foreach($permissions as $permission){
				    	array_push($ids, $permission->id);
				    }
					$newRoleItem->permissions()->sync($ids);
	            }
	            if($newRoleItem['slug'] == 'accounting'){
	            	$slugs = [
                        'view_expenses',
                        'read_expenses',
                        'create_expenses',
                        'edit_expenses',
                        'delete_expenses',
                        'view_employees',
                        'read_employees',
                        'create_employees',
                        'edit_employees',
                        'delete_employees',
                        'view_branches',
                        'read_branches',
                        'create_branches',
                        'edit_branches',
                        'accounting_dashboard',
                    ];
	                $permissions = DB::table('permissions')
                    ->whereIn('slug', $slugs)
                    ->select('id')->get();
                    $ids = [];
				    foreach($permissions as $permission){
				    	array_push($ids, $permission->id);
				    }
					$newRoleItem->permissions()->sync($ids);
	            }
            }

        }

    }
}
