<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $useUuid = config()->boolean('mock-permissions.uuids');
        $teams = config()->boolean('mock-permissions.teams');

        Schema::create('permissions', function (Blueprint $table) use ($useUuid) {

            $useUuid ? $table->uuid('id')->primary()->unique() : $table->id(); // permission id

            $table->string('name');       // For MySQL 8.0 use string('name', 125);
            $table->string('guard_name'); // For MySQL 8.0 use string('guard_name', 125);
            $table->timestamps();

            $table->unique(['name', 'guard_name']);
        });

        Schema::create('roles', function (Blueprint $table) use ($teams, $useUuid) {

            $useUuid ? $table->uuid('id')->primary()->unique() : $table->id(); // role id

            if ($teams) { // permission.testing is a fix for sqlite testing
                $table->unsignedBigInteger('team_id')->nullable();
                $table->index('team_id', 'roles_team_foreign_key_index');
            }

            $table->string('name');       // For MySQL 8.0 use string('name', 125);
            $table->string('guard_name'); // For MySQL 8.0 use string('guard_name', 125);
            $table->timestamps();

            if ($teams) {
                $table->unique(['team_id', 'name', 'guard_name']);
            } else {
                $table->unique(['name', 'guard_name']);
            }
        });

        Schema::create('model_has_permissions', function (Blueprint $table) use ($teams, $useUuid) {

            if ($useUuid) {
                $table->uuid('permission_id');
                $table->uuid('model_id');
            } else {
                $table->unsignedBigInteger('permission_id');
                $table->unsignedBigInteger('model_id');
            }

            $table->string('model_type');

            $table->index(['model_id', 'model_type'], 'model_has_permissions_model_id_model_type_index');

            $table->foreign('permission_id')
                ->references('id') // permission id
                ->on('permissions')
                ->onDelete('cascade');

            if ($teams) {
                $table->unsignedBigInteger('team_id');
                $table->index('team_id', 'model_has_permissions_team_foreign_key_index');
                $table->primary(['team_id', 'permission_id', 'model_id', 'model_type'],
                    'model_has_permissions_permission_model_type_primary');
            } else {
                $table->primary(['permission_id', 'model_id', 'model_type'],
                    'model_has_permissions_permission_model_type_primary');
            }
        });

        Schema::create('model_has_roles', function (Blueprint $table) use ($teams, $useUuid) {
            if ($useUuid) {
                $table->uuid('role_id');
                $table->uuid('model_id');
            } else {
                $table->unsignedBigInteger('role_id');
                $table->unsignedBigInteger('model_id');
            }

            $table->string('model_type');

            $table->index(['model_id', 'model_type'], 'model_has_roles_model_id_model_type_index');

            $table->foreign('role_id')
                ->references('id') // role id
                ->on('roles')
                ->onDelete('cascade');

            if ($teams) {
                $table->unsignedBigInteger('team_id');
                $table->index('team_id', 'model_has_roles_team_foreign_key_index');

                $table->primary(['team_id', 'role_id', 'model_id', 'model_type'],
                    'model_has_roles_role_model_type_primary');
            } else {
                $table->primary(['role_id', 'model_id', 'model_type'],
                    'model_has_roles_role_model_type_primary');
            }
        });

        Schema::create('role_has_permissions', function (Blueprint $table) use ($useUuid) {
            if ($useUuid) {
                $table->uuid('permission_id');
                $table->uuid('role_id');
            } else {
                $table->unsignedBigInteger('permission_id');
                $table->unsignedBigInteger('role_id');
            }

            $table->foreign('permission_id')
                ->references('id') // permission id
                ->on('permissions')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id') // role id
                ->on('roles')
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id'], 'role_has_permissions_permission_id_role_id_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('role_has_permissions');
        Schema::drop('model_has_roles');
        Schema::drop('model_has_permissions');
        Schema::drop('roles');
        Schema::drop('permissions');
    }
};
