<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExternalInspectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('ent_external_inspections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('externalControllerType');
            $table->text('desc')->nullable();
            $table->uuid('object');
            $table->uuid('sourceDepartment');
            #$table->uuid('status');
            #$table->timestamp('statusDate');
            $table->uuid('author');
            $table->timestamps();
            $table->softDeletes();
            #$table->uuid('createdAt');
            #$table->uuid('updatedAt');

            $table->foreign('externalControllerType')->references('id')->on('ent_external_controllers_types');
            $table->foreign('object')->references('id')->on('ent_objects');
            $table->foreign('sourceDepartment')->references('id')->on('ent_departments');
            $table->foreign('author')->references('id')->on('ent_users');
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
        Schema::dropIfExists('ent_external_inspections');
    }
}
