<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('ent_processes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('parentProcess')->nullable();
            $table->longText('path');
            $table->integer('level');
            $table->uuid('type');
            $table->string('sasId')->unique();
            $table->string('bpmId')->unique();
            $table->text('name');
            $table->text('code');
            $table->uuid('owner');
            $table->timestamp('validFrom');
            $table->timestamp('validUntil')->nullable();
            $table->uuid('author');
            $table->timestamps();
            $table->softDeletes();
            #$table->timestamp('createdAt');
            #$table->timestamp('updatedAt');

            $table->unique('id');
            $table->foreign('parentProcess')->references('id')->on('ent_processes');
            $table->foreign('type')->references('id')->on('ent_processes_types');
            $table->foreign('owner')->references('id')->on('ent_vacancies');
            $table->foreign('author')->references('id')->on('ent_users');
            $table->index(['parentProcess', 'bpmId', 'owner', 'author']);
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
        Schema::dropIfExists('ent_processes');
    }
}
