<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExternalInspectionProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('rel_external_inspection_process', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('inspection')->constrained('external_inspections');
            $table->uuid('process')->constrained('processes');
            $table->uuid('author')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            #$table->timestamp('createdAt');
            #$table->timestamp('updatedAt');

            $table->foreign('inspection')->references('id')->on('ent_external_inspections');
            $table->foreign('process')->references('id')->on('ent_processes');
            $table->foreign('author')->references('id')->on('ent_users');
            $table->index(['inspection', 'process', 'author']);
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
        Schema::dropIfExists('rel_external_inspection_process');
    }
}
