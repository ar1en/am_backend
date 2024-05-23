<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternalInspectionProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('rel_internal_inspection_process', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('inspection');
            $table->uuid('process');
            $table->uuid('author');
            $table->timestamps();
            $table->softDeletes();
            #$table->timestamp('createdAt');
            #$table->timestamp('updatedAt');

            $table->foreign('inspection')->references('id')->on('ent_internal_inspections');
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
        Schema::dropIfExists('rel_internal_inspection_process');
    }
}
