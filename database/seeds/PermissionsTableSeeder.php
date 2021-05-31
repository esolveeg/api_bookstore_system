<?php

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        /*
         * Permission Types
         *
         */
        $Permissionitems = [
            [
                'name'        => 'View users',
                'slug'        => 'view_users',
                'description' => 'Can view users',
                'model'       => 'User',
            ],
            [
                'name'        => 'Create users',
                'slug'        => 'create_users',
                'description' => 'Can create new users',
                'model'       => 'User',
            ],
            [
                'name'        => 'Edit users',
                'slug'        => 'edit_users',
                'description' => 'Can edit users',
                'model'       => 'User',
            ],
            [
                'name'        => 'Delete users',
                'slug'        => 'delete_users',
                'description' => 'Can delete users',
                'model'       => 'User',
            ],
            [
                'name'        => 'Read users',
                'slug'        => 'read_users',
                'description' => 'Can read users',
                'model'       => 'User',
            ],


            /////////////
            [
                'name'        => 'View orders',
                'slug'        => 'view_orders',
                'description' => 'Can view orders',
                'model'       => 'Order',
            ],
            [
                'name'        => 'View deleted orders',
                'slug'        => 'view_deleted_orders',
                'description' => 'Can view orders',
                'model'       => 'Order',
            ],
            [
                'name'        => 'Create orders',
                'slug'        => 'create_orders',
                'description' => 'Can create new orders',
                'model'       => 'Order',
            ],
            [
                'name'        => 'Edit orders',
                'slug'        => 'edit_orders',
                'description' => 'Can edit orders',
                'model'       => 'Order',
            ],
            [
                'name'        => 'Delete orders',
                'slug'        => 'delete_orders',
                'description' => 'Can delete orders',
                'model'       => 'Order',
            ],
            [
                'name'        => 'Read orders',
                'slug'        => 'read_orders',
                'description' => 'Can read orders',
                'model'       => 'Order',
            ],
            [
                'name'        => 'Deleted orders',
                'slug'        => 'deleted_orders',
                'description' => 'Can browse deleted orders',
                'model'       => 'Order',
            ],


            /////////////
            /////////////
            [
                'name'        => 'View expenses',
                'slug'        => 'view_expenses',
                'description' => 'Can view expenses',
                'model'       => 'Expense',
            ],
            [
                'name'        => 'View deleted expenses',
                'slug'        => 'view_deleted_expenses',
                'description' => 'Can view deleted expenses',
                'model'       => 'Expense',
            ],
            [
                'name'        => 'Create expenses',
                'slug'        => 'create_expenses',
                'description' => 'Can create new expenses',
                'model'       => 'Expense',
            ],
            [
                'name'        => 'Edit expenses',
                'slug'        => 'edit_expenses',
                'description' => 'Can edit expenses',
                'model'       => 'Expense',
            ],
            [
                'name'        => 'Delete expenses',
                'slug'        => 'delete_expenses',
                'description' => 'Can delete expenses',
                'model'       => 'Expense',
            ],
            [
                'name'        => 'Read expenses',
                'slug'        => 'read_expenses',
                'description' => 'Can read expenses',
                'model'       => 'Expense',
            ],
            [
                'name'        => 'Deleted expenses',
                'slug'        => 'read_expenses',
                'description' => 'Can browse deleted expenses',
                'model'       => 'Expense',
            ],

            /////////////
            /////////////
            [
                'name'        => 'View roles',
                'slug'        => 'view_roles',
                'description' => 'Can view roles',
                'model'       => 'Expense',
            ],
            [
                'name'        => 'View deleted roles',
                'slug'        => 'view_deleted_roles',
                'description' => 'Can view deleted roles',
                'model'       => 'Expense',
            ],
            [
                'name'        => 'Create roles',
                'slug'        => 'create_roles',
                'description' => 'Can create new roles',
                'model'       => 'Expense',
            ],
            [
                'name'        => 'Edit roles',
                'slug'        => 'edit_roles',
                'description' => 'Can edit roles',
                'model'       => 'Expense',
            ],
            [
                'name'        => 'Delete roles',
                'slug'        => 'delete_roles',
                'description' => 'Can delete roles',
                'model'       => 'Expense',
            ],
            [
                'name'        => 'Read roles',
                'slug'        => 'read_roles',
                'description' => 'Can read roles',
                'model'       => 'Expense',
            ],
            [
                'name'        => 'Deleted roles',
                'slug'        => 'read_roles',
                'description' => 'Can browse deleted roles',
                'model'       => 'Expense',
            ],

            /////////////
            /////////////
            [
                'name'        => 'View deleted products',
                'slug'        => 'view_deleted_products',
                'description' => 'Can view products',
                'model'       => 'Product',
            ],
            [
                'name'        => 'View products',
                'slug'        => 'view_products',
                'description' => 'Can view products',
                'model'       => 'Product',
            ],
            [
                'name'        => 'Create products',
                'slug'        => 'create_products',
                'description' => 'Can create new products',
                'model'       => 'Product',
            ],
            [
                'name'        => 'Edit products',
                'slug'        => 'edit_products',
                'description' => 'Can edit products',
                'model'       => 'Product',
            ],
            [
                'name'        => 'Delete products',
                'slug'        => 'delete_products',
                'description' => 'Can delete products',
                'model'       => 'Product',
            ],
            [
                'name'        => 'Read products',
                'slug'        => 'read_products',
                'description' => 'Can read products',
                'model'       => 'Product',
            ],
            [
                'name'        => 'Deleted products',
                'slug'        => 'deleted_products',
                'description' => 'Can browse deleted products',
                'model'       => 'Product',
            ],

            /////////////
            /////////////
            [
                'name'        => 'View customers',
                'slug'        => 'view_customers',
                'description' => 'Can view customers',
                'model'       => 'Customer',
            ],
            [
                'name'        => 'view deleted customers',
                'slug'        => 'view_deleted_customers',
                'description' => 'Can view deleted customers',
                'model'       => 'Customer',
            ],
            [
                'name'        => 'Create customers',
                'slug'        => 'create_customers',
                'description' => 'Can create new customers',
                'model'       => 'Customer',
            ],
            [
                'name'        => 'Edit customers',
                'slug'        => 'edit_customers',
                'description' => 'Can edit customers',
                'model'       => 'Customer',
            ],
            [
                'name'        => 'Delete customers',
                'slug'        => 'delete_customers',
                'description' => 'Can delete customers',
                'model'       => 'Customer',
            ],
            [
                'name'        => 'Read customers',
                'slug'        => 'read_customers',
                'description' => 'Can read customers',
                'model'       => 'Customer',
            ],
            [
                'name'        => 'Deleted customers',
                'slug'        => 'deleted_customers',
                'description' => 'Can browse deleted customers',
                'model'       => 'Customer',
            ],

            /////////////
            [
                'name'        => 'View outcoming orders',
                'slug'        => 'view_outcoming_orders',
                'description' => 'Can view outcoming orders',
                'model'       => 'OutComingOrder',
            ],
            [
                'name'        => 'View deleted outcoming orders',
                'slug'        => 'view_deleted_outcoming_orders',
                'description' => 'Can view outcoming orders',
                'model'       => 'OutComingOrder',
            ],
            [
                'name'        => 'Create outcoming orders',
                'slug'        => 'create_outcoming_orders',
                'description' => 'Can create new outcoming orders',
                'model'       => 'OutComingOrder',
            ],
            [
                'name'        => 'Edit outcoming orders',
                'slug'        => 'edit_outcoming_orders',
                'description' => 'Can edit outcoming orders',
                'model'       => 'OutComingOrder',
            ],
            [
                'name'        => 'Delete outcoming orders',
                'slug'        => 'delete_outcoming_orders',
                'description' => 'Can delete outcoming orders',
                'model'       => 'OutComingOrder',
            ],
            [
                'name'        => 'Read outcoming orders',
                'slug'        => 'read_outcoming_orders',
                'description' => 'Can read outcoming orders',
                'model'       => 'OutComingOrder',
            ],
            [
                'name'        => 'Deleted outcoming orders',
                'slug'        => 'deleted_outcoming_orders',
                'description' => 'Can browse deleted outcoming orders',
                'model'       => 'OutComingOrder',
            ],

            /////////////
            /// 
            [
                'name'        => 'View discounts',
                'slug'        => 'view_discounts',
                'description' => 'Can view discounts',
                'model'       => 'Discount',
            ],
            [
                'name'        => 'Create discounts',
                'slug'        => 'create_discounts',
                'description' => 'Can create new discounts',
                'model'       => 'Discount',
            ],
            [
                'name'        => 'Edit discounts',
                'slug'        => 'edit_discounts',
                'description' => 'Can edit discounts',
                'model'       => 'Discount',
            ],
            [
                'name'        => 'Delete discounts',
                'slug'        => 'delete_discounts',
                'description' => 'Can delete discounts',
                'model'       => 'Discount',
            ],
            [
                'name'        => 'Read discounts',
                'slug'        => 'read_discounts',
                'description' => 'Can read discounts',
                'model'       => 'Discount',
            ],

            /////////////
            [
                'name'        => 'View suppliers',
                'slug'        => 'view_suppliers',
                'description' => 'Can view suppliers',
                'model'       => 'Supplier',
            ],
            [
                'name'        => 'Create suppliers',
                'slug'        => 'create_suppliers',
                'description' => 'Can create new suppliers',
                'model'       => 'Supplier',
            ],
            [
                'name'        => 'Edit suppliers',
                'slug'        => 'edit_suppliers',
                'description' => 'Can edit suppliers',
                'model'       => 'Supplier',
            ],
            [
                'name'        => 'Delete suppliers',
                'slug'        => 'delete_suppliers',
                'description' => 'Can delete suppliers',
                'model'       => 'Supplier',
            ],
            [
                'name'        => 'Read suppliers',
                'slug'        => 'read_suppliers',
                'description' => 'Can read suppliers',
                'model'       => 'Supplier',
            ],

           
            /////////////
            [
                'name'        => 'View branches',
                'slug'        => 'view_branches',
                'description' => 'Can view branches',
                'model'       => 'Branch',
            ],
            [
                'name'        => 'Create branches',
                'slug'        => 'create_branches',
                'description' => 'Can create new branches',
                'model'       => 'Branch',
            ],
            [
                'name'        => 'Edit branches',
                'slug'        => 'edit_branches',
                'description' => 'Can edit branches',
                'model'       => 'Branch',
            ],
            [
                'name'        => 'Delete branches',
                'slug'        => 'delete_branches',
                'description' => 'Can delete branches',
                'model'       => 'Branch',
            ],
            [
                'name'        => 'Read branches',
                'slug'        => 'read_branches',
                'description' => 'Can read branches',
                'model'       => 'Branch',
            ],

            /////////////
            [
                'name'        => 'View transactions',
                'slug'        => 'view_transactions',
                'description' => 'Can view transactions',
                'model'       => 'Transaction',
            ],
            [
                'name'        => 'Create transactions',
                'slug'        => 'create_transactions',
                'description' => 'Can create new transactions',
                'model'       => 'Transaction',
            ],
            [
                'name'        => 'Edit transactions',
                'slug'        => 'edit_transactions',
                'description' => 'Can edit transactions',
                'model'       => 'Transaction',
            ],
            [
                'name'        => 'Delete transactions',
                'slug'        => 'delete_transactions',
                'description' => 'Can delete transactions',
                'model'       => 'Transaction',
            ],
            [
                'name'        => 'Read transactions',
                'slug'        => 'read_transactions',
                'description' => 'Can read transactions',
                'model'       => 'Transaction',
            ],
            [
                'name'        => 'Approve transactions',
                'slug'        => 'approve_transactions',
                'description' => 'Can approve transactions',
                'model'       => 'Transaction',
            ],
            [
                'name'        => 'Deleted transactions',
                'slug'        => 'deleted_transactions',
                'description' => 'Can browse deleted transactions',
                'model'       => 'Transaction',
            ],

            /////////////

            [
                'name'        => 'View employees',
                'slug'        => 'view_employees',
                'description' => 'Can view employees',
                'model'       => 'Employee',
            ],
            [
                'name'        => 'Create employees',
                'slug'        => 'create_employees',
                'description' => 'Can create new employees',
                'model'       => 'Employee',
            ],
            [
                'name'        => 'Edit employees',
                'slug'        => 'edit_employees',
                'description' => 'Can edit employees',
                'model'       => 'Employee',
            ],
            [
                'name'        => 'Delete employees',
                'slug'        => 'delete_employees',
                'description' => 'Can delete employees',
                'model'       => 'Employee',
            ],
            [
                'name'        => 'Read employees',
                'slug'        => 'read_employees',
                'description' => 'Can read employees',
                'model'       => 'Employee',
            ],

            /////////////
            [
                'name'        => 'View rebates',
                'slug'        => 'view_rebates',
                'description' => 'Can view rebates',
                'model'       => 'Rebate',
            ],
            [
                'name'        => 'Create rebates',
                'slug'        => 'create_rebates',
                'description' => 'Can create new rebates',
                'model'       => 'Rebate',
            ],
            [
                'name'        => 'Edit rebates',
                'slug'        => 'edit_rebates',
                'description' => 'Can edit rebates',
                'model'       => 'Rebate',
            ],
            [
                'name'        => 'Delete rebates',
                'slug'        => 'delete_rebates',
                'description' => 'Can delete rebates',
                'model'       => 'Rebate',
            ],
            [
                'name'        => 'Read rebates',
                'slug'        => 'read_rebates',
                'description' => 'Can read rebates',
                'model'       => 'Rebate',
            ],
            [
                'name'        => 'Deleted rebates',
                'slug'        => 'deleted_rebates',
                'description' => 'Can browse deleted rebates',
                'model'       => 'Rebate',
            ],

            /////////////
            [
                'name'        => 'Accounting Dashboard',
                'slug'        => 'accounting_dashboard',
                'description' => 'Can View accounting dashboard ',
                'model'       => 'Permission',
            ],
            [
                'name'        => 'Super Admin Dashboard',
                'slug'        => 'admin_dashboard',
                'description' => 'Can view super admin dashboard',
                'model'       => 'Permission',
            ],
            [
                'name'        => 'P.O.S Dashboard',
                'slug'        => 'pos_dashboard',
                'description' => 'Can view P.O.S dashboard',
                'model'       => 'Permission',
            ],

        ];

        /*
         * Add Permission Items
         *
         */
        foreach ($Permissionitems as $Permissionitem) {
            $newPermissionitem = Permission::where('slug', '=', $Permissionitem['slug'])->first();
            if ($newPermissionitem === null) {
                $newPermissionitem = Permission::create([
                    'name'          => $Permissionitem['name'],
                    'slug'          => $Permissionitem['slug'],
                    'description'   => $Permissionitem['description'],
                    'model'         => $Permissionitem['model'],
                ]);
            }
        }
    }
}
