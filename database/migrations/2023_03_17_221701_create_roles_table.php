<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('ent_roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unique('id');
            $table->foreignUuid('parentRole')->nullable()->constrained('ent_roles');
            $table->foreignUuid('department')->nullable()->constrained('ent_departments');
            $table->foreignUuid('object')->nullable()->constrained('ent_objects');
            $table->foreignUuid('process')->nullable()->constrained('ent_processes');
            $table->string('name');
            $table->foreignUuid('author')->constrained('ent_users');
            $table->timestamp('validFrom');
            $table->timestamp('validUntil')->nullable();
            $table->timestamps();
            $table->softDeletes();


            $table->index(['parentRole', 'author']);
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
        Schema::dropIfExists('ent_roles');
    }
}
