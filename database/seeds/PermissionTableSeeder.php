<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'applicant-list',
            'applicant-add',
            'applicant-show',
            'applicant-edit',
            'applicant-delete',
            'applicant-mail',
            'employee-list',
            'employee-add',
            'employee-show',
            'employee-edit',
            'employee-delete',
            'employee-mail',
            'testimonial-list',
            'forms-list',
            'forms-add',
            'forms-show',
            'forms-edit',
            'forms-delete',
            'application-status-list',
            'application-status-add',
            'application-status-show',
            'application-status-edit',
            'application-status-delete',
            'applicant-profile-form',
            'roles-permissions-list',
            'transactions',
            'reseller',
            'email-settings'
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
