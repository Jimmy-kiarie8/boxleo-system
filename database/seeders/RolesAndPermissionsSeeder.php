<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        /*
        Permission::create(['guard_name' => 'web', 'name' =>  'delete users']);
        Permission::create(['guard_name' => 'web', 'name' =>  'edit users']);
        Permission::create(['guard_name' => 'web', 'name' =>  'create users']);
        Permission::create(['guard_name' => 'web', 'name' =>  'view users']);

        Permission::create(['guard_name' => 'web', 'name' =>  'create subscribers']);
        Permission::create(['guard_name' => 'web', 'name' =>  'delete subscribers']);
        Permission::create(['guard_name' => 'web', 'name' =>  'view subscribers']);
        Permission::create(['guard_name' => 'web', 'name' =>  'edit subscriber']);

        Permission::create(['guard_name' => 'web', 'name' =>  'view orders']);
        Permission::create(['guard_name' => 'web', 'name' =>  'delete orders']);
        Permission::create(['guard_name' => 'web', 'name' =>  'create orders']);
        Permission::create(['guard_name' => 'web', 'name' =>  'edit orders']);

        Permission::create(['guard_name' => 'web', 'name' =>  'edit scan']);
        Permission::create(['guard_name' => 'web', 'name' =>  'inscan']);
        Permission::create(['guard_name' => 'web', 'name' =>  'outscan']);

        Permission::create(['guard_name' => 'web', 'name' =>  'view sales']);
        Permission::create(['guard_name' => 'web', 'name' =>  'create sales']);
        Permission::create(['guard_name' => 'web', 'name' =>  'edit sales']);
        Permission::create(['guard_name' => 'web', 'name' =>  'delete sales']);

        Permission::create(['guard_name' => 'web', 'name' =>  'create roles']);
        Permission::create(['guard_name' => 'web', 'name' =>  'view roles']);
        Permission::create(['guard_name' => 'web', 'name' =>  'delete roles']);
        Permission::create(['guard_name' => 'web', 'name' =>  'edit roles']);

        Permission::create(['guard_name' => 'web', 'name' =>  'upload excel']);
        Permission::create(['guard_name' => 'web', 'name' =>  'update status']);

        Permission::create(['guard_name' => 'web', 'name' =>  'view finance']);
        Permission::create(['guard_name' => 'web', 'name' =>  'view reports']);
        Permission::create(['guard_name' => 'web', 'name' =>  'send sms']);
        Permission::create(['guard_name' => 'web', 'name' =>  'view logs']);



        Permission::create(['guard_name' => 'web', 'name' =>  'dispatch']);
        Permission::create(['guard_name' => 'web', 'name' =>  'view ']);
        Permission::create(['guard_name' => 'web', 'name' =>  'view clients']);
        Permission::create(['guard_name' => 'web', 'name' =>  'manage inventory']);
        Permission::create(['guard_name' => 'web', 'name' =>  'view products']);
        Permission::create(['guard_name' => 'web', 'name' =>  'view vendors']);
        Permission::create(['guard_name' => 'web', 'name' =>  'manage users']);
        Permission::create(['guard_name' => 'web', 'name' =>  'manage company']);
        Permission::create(['guard_name' => 'web', 'name' =>  'view settings']);
        Permission::create(['guard_name' => 'web', 'name' =>  'view warehouses']);
        Permission::create(['guard_name' => 'web', 'name' =>  'view riders']);
        Permission::create(['guard_name' => 'web', 'name' =>  'view status']);
        Permission::create(['guard_name' => 'web', 'name' =>  'manage services']);
        Permission::create(['guard_name' => 'web', 'name' =>  'view services']);
        Permission::create(['guard_name' => 'web', 'name' =>  'view zones']);
        Permission::create(['guard_name' => 'web', 'name' =>  'manage automation']);
        Permission::create(['guard_name' => 'web', 'name' =>  'view automations']);
        Permission::create(['guard_name' => 'web', 'name' =>  'view sms']);
        Permission::create(['guard_name' => 'web', 'name' =>  'view emails']);
        Permission::create(['guard_name' => 'web', 'name' =>  'manage reports']);
        Permission::create(['guard_name' => 'web', 'name' =>  'view custom reports']);
        Permission::create(['guard_name' => 'web', 'name' =>  'print waybills']); */


        Permission::create(['guard_name' => 'web', 'name' => 'Order view']);
        Permission::create(['guard_name' => 'web', 'name' => 'Order edit']);
        Permission::create(['guard_name' => 'web', 'name' => 'Order update status']);
        Permission::create(['guard_name' => 'web', 'name' => 'Order assign rider']);
        Permission::create(['guard_name' => 'web', 'name' => 'Order assign']);
        Permission::create(['guard_name' => 'web', 'name' => 'Order view details']);
        Permission::create(['guard_name' => 'web', 'name' => 'Order force delete']);
        Permission::create(['guard_name' => 'web', 'name' => 'Order restore']);
        Permission::create(['guard_name' => 'web', 'name' => 'Order delete']);
        Permission::create(['guard_name' => 'web', 'name' => 'Order filter by OU']);
        Permission::create(['guard_name' => 'web', 'name' => 'Order filter by zone']);
        Permission::create(['guard_name' => 'web', 'name' => 'Order filter by vendor']);
        Permission::create(['guard_name' => 'web', 'name' => 'Order filter by status']);
        Permission::create(['guard_name' => 'web', 'name' => 'Order create']);
        Permission::create(['guard_name' => 'web', 'name' => 'Order upload']);
        Permission::create(['guard_name' => 'web', 'name' => 'Order sheets upload']);
        Permission::create(['guard_name' => 'web', 'name' => 'Order shopify upload']);
        Permission::create(['guard_name' => 'web', 'name' => 'Order woocommerce upload']);
        Permission::create(['guard_name' => 'web', 'name' => 'Order dispatch']);
        Permission::create(['guard_name' => 'web', 'name' => 'Order update delivered or returned']);
        Permission::create(['guard_name' => 'web', 'name' => 'Order print edit']);
        Permission::create(['guard_name' => 'web', 'name' => 'Order scan']);
        Permission::create(['guard_name' => 'web', 'name' => 'Product woocommerce upload']);
        Permission::create(['guard_name' => 'web', 'name' => 'Product shopify upload']);
        Permission::create(['guard_name' => 'web', 'name' => 'Product create']);
        Permission::create(['guard_name' => 'web', 'name' => 'Product edit']);
        Permission::create(['guard_name' => 'web', 'name' => 'Product quantity update']);
        Permission::create(['guard_name' => 'web', 'name' => 'Product delete']);
        Permission::create(['guard_name' => 'web', 'name' => 'Products view']);
        Permission::create(['guard_name' => 'web', 'name' => 'User create']);
        Permission::create(['guard_name' => 'web', 'name' => 'User edit']);
        Permission::create(['guard_name' => 'web', 'name' => 'User permissions']);
        Permission::create(['guard_name' => 'web', 'name' => 'User reset password']);
        Permission::create(['guard_name' => 'web', 'name' => 'User delete']);
        Permission::create(['guard_name' => 'web', 'name' => 'User restore']);
        Permission::create(['guard_name' => 'web', 'name' => 'User force delete']);
        Permission::create(['guard_name' => 'web', 'name' => 'Client create']);
        Permission::create(['guard_name' => 'web', 'name' => 'Client edit']);
        Permission::create(['guard_name' => 'web', 'name' => 'Client delete']);
        Permission::create(['guard_name' => 'web', 'name' => 'Rider create']);
        Permission::create(['guard_name' => 'web', 'name' => 'Role create']);
        Permission::create(['guard_name' => 'web', 'name' => 'Role edit']);
        Permission::create(['guard_name' => 'web', 'name' => 'Role delete']);
        Permission::create(['guard_name' => 'web', 'name' => 'Vendor create']);
        Permission::create(['guard_name' => 'web', 'name' => 'Vendor edit']);
        Permission::create(['guard_name' => 'web', 'name' => 'Vendor delete']);
        Permission::create(['guard_name' => 'web', 'name' => 'manage company']);
        Permission::create(['guard_name' => 'web', 'name' => 'manage api']);
        Permission::create(['guard_name' => 'web', 'name' => 'OU create']);
        Permission::create(['guard_name' => 'web', 'name' => 'OU edit']);
        Permission::create(['guard_name' => 'web', 'name' => 'OU deactivate']);
        Permission::create(['guard_name' => 'web', 'name' => 'Invoices view']);
        Permission::create(['guard_name' => 'web', 'name' => 'Return view']);
        Permission::create(['guard_name' => 'web', 'name' => 'Manage inventory']);
        Permission::create(['guard_name' => 'web', 'name' => 'Vendors view']);
        Permission::create(['guard_name' => 'web', 'name' => 'Manage user']);
        Permission::create(['guard_name' => 'web', 'name' => 'User view']);
        Permission::create(['guard_name' => 'web', 'name' => 'Role view']);
        Permission::create(['guard_name' => 'web', 'name' => 'Manage company']);
        Permission::create(['guard_name' => 'web', 'name' => 'Setting view']);
        Permission::create(['guard_name' => 'web', 'name' => 'Warehouse view']);
        Permission::create(['guard_name' => 'web', 'name' => 'Rider view']);
        Permission::create(['guard_name' => 'web', 'name' => 'Status view']);
        Permission::create(['guard_name' => 'web', 'name' => 'Manage service']);
        Permission::create(['guard_name' => 'web', 'name' => 'Service view']);
        Permission::create(['guard_name' => 'web', 'name' => 'Zone view']);
        Permission::create(['guard_name' => 'web', 'name' => 'Manage automation']);
        Permission::create(['guard_name' => 'web', 'name' => 'Automation view']);
        Permission::create(['guard_name' => 'web', 'name' => 'Sms view']);
        Permission::create(['guard_name' => 'web', 'name' => 'Email view']);
        Permission::create(['guard_name' => 'web', 'name' => 'Manage reports']);
        Permission::create(['guard_name' => 'web', 'name' => 'Custom Report']);
        Permission::create(['guard_name' => 'web', 'name' => 'Report view']);
        Permission::create(['guard_name' => 'web', 'name' => 'Print waybills']);
        Permission::create(['guard_name' => 'web', 'name' => 'Manage Billings']);
        Permission::create(['guard_name' => 'web', 'name' => 'Manage Warehouse']);
        Permission::create(['guard_name' => 'web', 'name' => 'Remittance']);
        Permission::create(['guard_name' => 'web', 'name' => 'Mpesa transactions']);
        Permission::create(['guard_name' => 'web', 'name' => 'Make calls']);
        Permission::create(['guard_name' => 'web', 'name' => 'Recordings']);
        Permission::create(['guard_name' => 'web', 'name' => 'Manage Marketplace']);
        Permission::create(['guard_name' => 'web', 'name' => 'Manage API']);
        Permission::create(['guard_name' => 'web', 'name' => 'Update Awaiting Return']);

        
        
        

        
        $role = Role::create(['name' => 'Super admin']);

        $role->givePermissionTo(Permission::all());

        $user = User::first();
        $user->assignRole('Super admin');


        Permission::create(['guard_name' => 'seller', 'name' => 'Order view']);
        Permission::create(['guard_name' => 'seller', 'name' => 'Order view details']);
        Permission::create(['guard_name' => 'seller', 'name' => 'Products view']);
        Permission::create(['guard_name' => 'seller', 'name' => 'Manage inventory']);
        Permission::create(['guard_name' => 'seller', 'name' => 'Invoices view']);
        Permission::create(['guard_name' => 'seller', 'name' => 'Return view']);
        Permission::create(['guard_name' => 'seller', 'name' => 'Report view']);
        Permission::create(['guard_name' => 'seller', 'name' => 'Custom Report']);
        Permission::create(['guard_name' => 'seller', 'name' => 'Order create']);
        Permission::create(['guard_name' => 'seller', 'name' => 'Order upload']);
        Permission::create(['guard_name' => 'seller', 'name' => 'Order sheets upload']);
        Permission::create(['guard_name' => 'seller', 'name' => 'Order shopify upload']);
        Permission::create(['guard_name' => 'seller', 'name' => 'Order woocommerce upload']);
        Permission::create(['guard_name' => 'seller', 'name' => 'Product create']);


        // create Super admin
        Role::create(['name' => 'Call center', 'guard_name' => 'web']);
        Role::create(['name' => 'Vendor', 'guard_name' => 'seller']);


        // $vendor->givePermissionTo(Permission::all());

        // this can be done as separate statements
        // $role = Role::create(['name' => 'Admin']);
        // $role->givePermissionTo('view users');

        // $role = Role::create(['guard_name' => 'clients', 'name' => 'Client']);
        // $role->givePermissionTo('view orders');

    }
}
