<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mission_orders', function (Blueprint $table) {
            $table->enum('status', ['draft', 'sup_approve', 'sg_approve', 'rejected', 'approved', 'paid'])
                ->default('draft')
                ->change();

            $table->enum('memor_status', ['draft', 'controller_approve', 'sg_approve', 'rejected', 'approved', 'paid'])
                ->nullable()
                ->change();
        });
        Schema::table('mission_approves', function (Blueprint $table) {
            $table->enum('status', ['draft', 'sup_approve', 'sg_approve', 'rejected', 'approved', 'paid'])
                ->nullable()
                ->change();

            $table->enum('memor_status', ['draft', 'controller_approve', 'sg_approve', 'rejected', 'approved', 'paid'])
                ->nullable()
                ->change();
        });
        Schema::table('tournees', function (Blueprint $table) {
            $table->enum('status', ['draft', 'sup_approve', 'sg_approve', 'rejected', 'approved', 'paid'])
                ->default('draft')
                ->change();

            $table->enum('memor_status', ['draft', 'controller_approve', 'sg_approve', 'rejected', 'approved', 'paid'])
                ->nullable()
                ->change();
        });
        Schema::table('tournee_approves', function (Blueprint $table) {
            $table->enum('status', ['draft', 'sup_approve', 'sg_approve', 'rejected', 'approved', 'paid'])
                ->nullable()
                ->change();

            $table->enum('memor_status', ['draft', 'controller_approve', 'sg_approve', 'rejected', 'approved', 'paid'])
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('mission_orders', function (Blueprint $table) {
            $table->enum('status', ['draft', 'sup_approve', 'hr_approve', 'sg_approve', 'rejected', 'approved', 'paid'])
                ->default('draft')
                ->change();

            $table->enum('memor_status', ['draft', 'sup_approve', 'hr_approve', 'sg_approve', 'rejected', 'approved', 'paid'])
                ->nullable()
                ->change();
        });
        Schema::table('mission_approves', function (Blueprint $table) {
            $table->enum('status', ['draft', 'sup_approve', 'hr_approve', 'sg_approve', 'rejected', 'approved', 'paid'])
                ->nullable()
                ->change();

            $table->enum('memor_status', ['draft', 'sup_approve', 'hr_approve', 'sg_approve', 'rejected', 'approved', 'paid'])
                ->nullable()
                ->change();
        });
        Schema::table('tournees', function (Blueprint $table) {
            $table->enum('status', ['draft', 'sup_approve', 'hr_approve', 'sg_approve', 'rejected', 'approved', 'paid'])
                ->default('draft')
                ->change();

            $table->enum('memor_status', ['draft', 'sup_approve', 'hr_approve', 'sg_approve', 'rejected', 'approved', 'paid'])
                ->nullable()
                ->change();
        });
        Schema::table('tournee_approves', function (Blueprint $table) {
            $table->enum('status', ['draft', 'sup_approve', 'hr_approve', 'sg_approve', 'rejected', 'approved', 'paid'])
                ->nullable()
                ->change();

            $table->enum('memor_status', ['draft', 'sup_approve', 'hr_approve', 'sg_approve', 'rejected', 'approved', 'paid'])
                ->nullable()
                ->change();
        });
    }
};
