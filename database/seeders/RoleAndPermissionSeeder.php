<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $mentorRole = Role::create(['name' => 'mentor']);
        $studentRole = Role::create(['name' => 'student']);


        $createCoursePermission = Permission::create(['name' => 'create-course']);
        $editCoursePermission = Permission::create(['name' => 'edit-course']);
        $deleteCoursePermission = Permission::create(['name' => 'delete-course']);
        $viewCoursePermission = Permission::create(['name' => 'view-course']);
        $manageEnrollmentsPermission = Permission::create(['name' => 'manage-enrollments']);
        $manageUsersPermission = Permission::create(['name' => 'manage-users']);
        $manageRolesPermission = Permission::create(['name' => 'manage-roles']);
        $manageStatsPermission = Permission::create(['name' => 'manage-stats']);

        $adminRole->givePermissionTo([
            $createCoursePermission, $editCoursePermission, $deleteCoursePermission, $viewCoursePermission,
            $manageEnrollmentsPermission, $manageUsersPermission, $manageRolesPermission, $manageStatsPermission
        ]);

        $mentorRole->givePermissionTo([
            $createCoursePermission, $editCoursePermission, $deleteCoursePermission, $viewCoursePermission,
            $manageEnrollmentsPermission
        ]);

        $studentRole->givePermissionTo([$viewCoursePermission]);
    }

   
}
