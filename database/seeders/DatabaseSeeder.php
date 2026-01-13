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
            ['name' => 'Manage Company', 'slug' => 'manage-company', 'group' => 'companies'],
            ['name' => 'Invite Users to Company', 'slug' => 'invite-users-to-company', 'group' => 'companies'],
            ['name' => 'Change Company user role', 'slug' => 'change-company-user-role', 'group' => 'companies'],
            ['name' => 'View Tasks', 'slug' => 'view-tasks', 'group' => 'tasks'],
            ['name' => 'Approve Tasks', 'slug' => 'approve-tasks', 'group' => 'tasks'],
        ];

        Permission::insert($permissions);

        $admin = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $manager = Role::create(['name' => 'Manager', 'slug' => 'manager']);

        $admin->permissions()->sync(Permission::pluck('id'));
        $manager->permissions()->sync(Permission::whereIn('slug', ['manage-company','invite-users-to-company', 'change-company-user-role'])->pluck('id')
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
