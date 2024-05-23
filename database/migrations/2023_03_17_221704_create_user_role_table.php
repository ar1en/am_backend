<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('rel_user_role', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user')->constrained('ent_users');
            $table->foreignUuid('role')->constrained('ent_roles');
            $table->foreignUuid('author')->constrained('ent_users');
            #$table->timestamp('createdAt');
            #$table->timestamp('updatedAt');
            $table->timestamp('validFrom');
            $table->timestamp('validUntil')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user', 'role', 'author']);
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
        Schema::dropIfExists('rel_user_role');
    }
}
