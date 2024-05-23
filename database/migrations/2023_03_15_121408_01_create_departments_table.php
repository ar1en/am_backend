<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('ent_departments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('parentDepartment')->nullable();
            $table->string('bpmId')->nullable();
            $table->string('sasId')->nullable();
            $table->text('name');
            $table->integer('level');
            $table->longText('path');
            #$table->uuid('assuranceMap')->constrained('assurance_maps');
            $table->uuid('author');
            $table->timestamps();
            $table->softDeletes();
            #$table->timestamp('createdAt');
            #$table->timestamp('updatedAt');

            $table->unique('id');
            $table->foreign('parentDepartment')->references('id')->on('ent_departments');
            $table->foreign('author')->references('id')->on('ent_users');
            $table->index(['parentDepartment', 'author']);
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
        Schema::dropIfExists('ent_departments');
    }
};
