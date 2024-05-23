<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIcsMatricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('ent_ics_matrices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('object');
            $table->uuid('process');
            $table->text('desc')->nullable();
            $table->boolean('ics');
            $table->boolean('impactOnRisk');
            $table->integer('testingYear')->nullable();
            $table->integer('updatingYear')->nullable();
            $table->uuid('author');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('object')->references('id')->on('ent_objects');
            $table->foreign('process')->references('id')->on('ent_processes');
            $table->foreign('author')->references('id')->on('ent_users');
            $table->index(['object', 'process']);
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
        Schema::dropIfExists('ent_ics_matrices');
    }
}
