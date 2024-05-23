<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollegiateBodyProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('rel_collegiate_body_process', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('collegiateBody');
            $table->uuid('process');
            $table->uuid('author');
            $table->timestamps();
            $table->softDeletes();
            #$table->timestamp('createdAt');
            #$table->timestamp('updatedAt');

            $table->foreign('collegiateBody')->references('id')->on('ent_collegiate_bodies');
            $table->foreign('process')->references('id')->on('ent_processes');
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
        Schema::dropIfExists('rel_collegiate_body_process');
    }
}
