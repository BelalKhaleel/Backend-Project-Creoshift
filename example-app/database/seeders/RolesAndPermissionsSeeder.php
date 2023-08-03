<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $visitorRole = Role::create(['name' => 'visitor']);

        // Create user permissions
        $createUsersPermission = Permission::create(['name' => 'create users']);
        $editUsersPermission = Permission::create(['name' => 'edit users']);
        $deleteUsersPermission = Permission::create(['name' => 'delete users']);

        // Create post permissions
        $createPostsPermission = Permission::create(['name' => 'create posts']);
        $editPostsPermission = Permission::create(['name' => 'edit posts']);
        $deletePostsPermission = Permission::create(['name' => 'delete posts']);

        // Create comment permissions
        $createCommentsPermission = Permission::create(['name' => 'create comments']);
        $editCommentsPermission = Permission::create(['name' => 'edit comments']);
        $editOwnCommentsPermission = Permission::create(['name' => 'edit own comments']);
        $deleteCommentsPermission = Permission::create(['name' => 'delete comments']);
        $deleteOwnCommentsPermission = Permission::create(['name' => 'delete own comments']);

        // Assign permissions to roles
        $adminRole->syncPermissions([$createUsersPermission,
                                     $editUsersPermission,
                                     $deleteUsersPermission,
                                     $createPostsPermission, 
                                     $editPostsPermission, 
                                     $deletePostsPermission,
                                     $createCommentsPermission,
                                     $editCommentsPermission,
                                     $deleteCommentsPermission
                                    ]);
                                    
        $visitorRole->syncPermissions([$createCommentsPermission,
                                       $editOwnCommentsPermission,
                                       $deleteOwnCommentsPermission]);
    }
}
