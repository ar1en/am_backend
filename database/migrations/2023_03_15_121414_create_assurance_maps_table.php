<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssuranceMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('ent_assurance_maps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('period');
            $table->string('name');
            $table->uuid('status');
            $table->timestamp('statusDate');
            $table->uuid('author');
            $table->timestamps();
            $table->softDeletes();
            #$table->timestamp('createdAt');
            #$table->timestamp('updatedAt');

            $table->foreign('period')->references('id')->on('ent_periods');
            $table->foreign('author')->references('id')->on('ent_users');
            $table->index('author');
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
        Schema::dropIfExists('ent_assurance_maps');
    }
}
