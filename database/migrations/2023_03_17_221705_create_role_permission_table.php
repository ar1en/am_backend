<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('rel_role_permission', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('role')->constrained('ent_roles');
            $table->foreignUuid('permission')->constrained('ent_permissions');
            $table->foreignUuid('author')->constrained('ent_users');
            #$table->timestamp('createdAt');
            #$table->timestamp('updatedAt');
            $table->timestamp('validFrom');
            $table->timestamp('validUntil');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['permission', 'author', 'role']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rel_role_permission');
    }
}
