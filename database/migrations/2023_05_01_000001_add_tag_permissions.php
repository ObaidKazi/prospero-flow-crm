<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create permissions
       // Permission::create(['name' => 'read tag']);
        //Permission::create(['name' => 'create tag']);
        ///Permission::create(['name' => 'update tag']);
        //Permission::create(['name' => 'delete tag']);

        // Assign permissions to admin role
        $adminRole = DB::table('role')->where('name', 'Admin')->first();
        if ($adminRole) {
            DB::table('role_has_permission')->insert([
                ['permission_id' => DB::table('permission')->where('name', 'read tag')->first()->id, 'role_id' => $adminRole->id],
                ['permission_id' => DB::table('permission')->where('name', 'create tag')->first()->id, 'role_id' => $adminRole->id],
                ['permission_id' => DB::table('permission')->where('name', 'update tag')->first()->id, 'role_id' => $adminRole->id],
                ['permission_id' => DB::table('permission')->where('name', 'delete tag')->first()->id, 'role_id' => $adminRole->id],
            ]);
        }

        // Assign permission to superadmin role
        $superAdminRole = DB::table('role')->where('name', 'SuperAdmin')->first();
        if ($superAdminRole) {
            DB::table('role_has_permission')->insert([
                ['permission_id' => DB::table('permission')->where('name', 'read tag')->first()->id, 'role_id' => $superAdminRole->id],
                ['permission_id' => DB::table('permission')->where('name', 'create tag')->first()->id, 'role_id' => $superAdminRole->id],
                ['permission_id' => DB::table('permission')->where('name', 'update tag')->first()->id, 'role_id' => $superAdminRole->id],
                ['permission_id' => DB::table('permission')->where('name', 'delete tag')->first()->id, 'role_id' => $superAdminRole->id],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Delete permissions
        Permission::where('name', 'read tag')->delete();
        Permission::where('name', 'create tag')->delete();
        Permission::where('name', 'update tag')->delete();
        Permission::where('name', 'delete tag')->delete();
    }
};