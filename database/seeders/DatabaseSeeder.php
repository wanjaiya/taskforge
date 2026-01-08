<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'Manage Users', 'slug' => 'manage-users', 'group' => 'users'],
            ['name' => 'Manage Workflows', 'slug' => 'manage-workflows', 'group' => 'workflows'],
            ['name' => 'View Tasks', 'slug' => 'view-tasks', 'group' => 'tasks'],
            ['name' => 'Approve Tasks', 'slug' => 'approve-tasks', 'group' => 'tasks'],
        ];

        Permission::insert($permissions);

        $admin = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $staff = Role::create(['name' => 'Staff', 'slug' => 'staff']);

        $admin->permissions()->sync(Permission::pluck('id'));
        $staff->permissions()->sync(Permission::whereIn('slug', ['view-tasks'])->pluck('id')
        );

       $user =  User::factory()->create([
            'name' => 'Admin Task Forge',
            'email' => 'admin@taskforge.com',
            'password' => bcrypt('Gilberts12'),
            'email_verified_at' => now(),
        ]);

       $user->roles()->sync([$admin->id]);

    }
}
