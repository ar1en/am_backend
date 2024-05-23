<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExternalInspectionObjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('rel_external_inspection_object', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('inspection');
            $table->uuid('object');
            $table->uuid('author');
            $table->timestamps();
            $table->softDeletes();
            #$table->timestamp('createdAt');
            #$table->timestamp('updatedAt');

            $table->foreign('inspection')->references('id')->on('ent_external_inspections');
            $table->foreign('object')->references('id')->on('ent_objects');
            $table->foreign('author')->references('id')->on('ent_users');
            $table->index(['inspection', 'object', 'author']);
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
        Schema::dropIfExists('rel_external_inspection_object');
    }
}
