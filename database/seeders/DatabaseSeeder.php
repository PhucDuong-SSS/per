<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker  = Faker::create();

        foreach (range(1, 10) as $index) {
            DB::table('organizations')->insert([
                'name' => $faker->word,
                'email' => $faker->email,
                'address' => $faker->address,
                'phone' => $faker->phoneNumber
            ]);
        }


        foreach (range(1, 5) as $index) {
            $organizations = Organization::all();
            DB::table('users')->insert([
                'organization_id' => $organizations->random()->id,
                'full_name' => $faker->name,
                'email' => $faker->email,
                'password' => bcrypt(123456)

            ]);
        }


        foreach (range(1, 5) as $index) {
            $users = User::all();
            DB::table('posts')->insert([
                'user_id' => $users->random()->id,
                'title' => $faker->word,
                'content' => $faker->word,
                'is_active' => $faker->randomElement([1,0])
            ]);
        }
        $role = new Role();
        $role->name = "Admin";
        $role->save();
        $role = new Role();
        $role->name = "organization_admin";
        $role->save();
        $role = new Role();
        $role->name = "writer";
        $role->save();

        $permission = new Permission();
        $permission->name = "add_user";
        $permission->save();
        $permission = new Permission();
        $permission->name = "edit_user";
        $permission->save();
        $permission = new Permission();
        $permission->name = "delete_user";
        $permission->save();
        $permission = new Permission();
        $permission->name = "add_organization";
        $permission->save();
        $permission = new Permission();
        $permission->name = "edit_organization";
        $permission->save();
        $permission = new Permission();
        $permission->name = "delete_organization";
        $permission->save();
        $permission = new Permission();
        $permission->name = "add_post";
        $permission->save();
        $permission = new Permission();
        $permission->name = "edit_post";
        $permission->save();
        $permission = new Permission();
        $permission->name = "delete_post";
        $role->save();


        foreach (range(1, 5) as $index) {
            $users = User::all();
            $roles = Role::all();

            DB::table('user_role')->insert([
                'user_id' => $users->random()->id,
                'role_id' => $roles->random()->id,
            ]);
        }

        foreach (range(1, 5) as $index) {
            $permissions = Permission::all();
            $roles = Role::all();

            DB::table('role_permission')->insert([
                'role_id' => $roles->random()->id,
                'permission_id' => $permissions->random()->id,
                
            ]);
        }
    }
}
