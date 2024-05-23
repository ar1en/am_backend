<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIcsWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('ent_ics_works', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('type');
            $table->uuid('object');
            $table->uuid('process');
            $table->mediumText('desc')->nullable();
            $table->text('executor')->nullable();
            $table->date('deadline')->nullable();
            $table->boolean('icsPerimeter')->nullable();
            #$table->uuid('status')->nullable();
            #$table->text('statusDesc');
            $table->uuid('author');
            $table->timestamps();
            $table->softDeletes();
            #$table->timestamp('createdAt');
            #$table->timestamp('updatedAt');

            $table->foreign('type')->references('id')->on('ent_ics_works_types');
            $table->foreign('object')->references('id')->on('ent_objects');
            $table->foreign('process')->references('id')->on('ent_processes');
            $table->foreign('author')->references('id')->on('ent_users');
            $table->index(['type', 'object', 'process', 'author']);
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
        Schema::dropIfExists('ent_ics_works');
    }
}
