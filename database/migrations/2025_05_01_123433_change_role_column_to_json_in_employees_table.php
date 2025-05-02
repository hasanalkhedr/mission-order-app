<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            // Remove the existing enum column
            $table->dropColumn('role');

            // Add new JSON column
            $table->json('roles')->nullable()->after('user_id');
            $table->index('roles', 'employees_roles_index');
        });

    }

    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex('employees_roles_index');
            $table->dropColumn('roles');
            $table->enum('role', ['employee', 'supervisor', 'hr', 'sg'])->nullable();
        });

        // Note: Down migration won't perfectly restore single roles from array
    }
};
