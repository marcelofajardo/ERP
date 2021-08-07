<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\User;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        $input_roles = $this->command->ask('Enter roles in comma separate format.', 'Admin,Selectors,Searchers,ImageCropers,Supervisors,Listers,Approvers,Inventory,Attribute,Sales,crm,message,Activity,user,Social Creator,Social Manager,HOD of CRM,Developer,Office Boy,Review,Delivery Coordinator,Products Lister,social-facebook-test,Vendor,Customer Care');
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'product-list',
            'product-create',
            'product-edit',
            'product-delete',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'selection-list',
            'selection-create',
            'selection-edit',
            'selection-delete',
            'searcher-list',
            'searcher-create',
            'searcher-edit',
            'searcher-delete',
            'setting-list',
            'setting-create',
            'supervisor-list',
            'supervisor-edit',
            'category-edit',
            'imagecropper-list',
            'imagecropper-create',
            'imagecropper-edit',
            'imagecropper-delete',
            'lister-list',
            'lister-edit',
            'approver-list',
            'approver-edit',
            'inventory-list',
            'inventory-edit',
            'attribute-list',
            'attribute-create',
            'attribute-edit',
            'attribute-delete',
            'view-activity',
            'brand-edit',
            'lead-create',
            'lead-edit',
            'lead-delete',
            'crm',
            'order-view',
            'order-create',
            'order-edit',
            'order-delete',
            'admin',
            'reply-edit',
            'purchase',
            'social-create',
            'social-manage',
            'social-view',
            'developer-tasks',
            'developer-all',
            'voucher',
            'review-view',
            'private-viewing',
            'delivery-approval',
            'product-lister',
            'vendor-all',
            'customer',
            'crop-approval',
            'crop-sequence',
            'approved-listing',
            'product-affiliate',
            'social-email',
            'facebook',
            'instagram',
            'sitejabber',
            'pinterest',
            'rejected-listing',
            'instagram-manual-comment',
            'lawyer-all',
            'case-all',
            'seo',
            'old',
            'old-incoming',
            'blogger-all',
            'mailchimp',
            'hubstaff'
        ];

        foreach ($permissions as $perms) {
            Permission::firstOrCreate(['name' => $perms]);
        }
        // Explode roles
        $roles_array = explode(',', $input_roles);

        // add roles
        foreach ($roles_array as $role) {
            $role = Role::firstOrCreate(['name' => trim($role)]);

            if ($role->name == 'Admin') {
                // assign all permissions
                //$role->syncPermissions(Permission::all());
                $role->syncPermissions(Permission::where('guard_name','!=','')->get());
                $this->command->info('Admin granted all the permissions');
            } else {
                // for others by default only read access
                $role->syncPermissions(Permission::where('name', 'LIKE', 'view_%')->get());
            }

            // create one user for each role
            $this->createUser($role);
        }

        $this->command->info('Roles ' . $input_roles . ' added successfully');
        $this->createUser('Admin');
    }

    /**
     * Create a user with given role
     *
     * @param $role
     */
    private function createUser($role)
    {
        $user = factory(User::class)->create();
        $user->assignRole('Admin');

// if( $role->name == 'Admin' ) {
        $this->command->info('Here is your admin details to login:');
        $this->command->warn($user->email);
        $this->command->warn('Password is "secret"');
// }
    }
}