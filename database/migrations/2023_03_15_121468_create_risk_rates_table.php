<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiskRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('rel_risk_rates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('risk');
            $table->uuid('process');
            $table->uuid('object');
            $table->double('rate');
            $table->uuid('author');
            $table->timestamps();
            $table->softDeletes();
            #$table->timestamp('createdAt');
            #$table->timestamp('updatedAt');

            $table->foreign('risk')->references('id')->on('ent_risks');
            $table->foreign('process')->references('id')->on('ent_processes');
            $table->foreign('object')->references('id')->on('ent_objects');
            $table->foreign('author')->references('id')->on('ent_users');
            $table->index(['risk', 'process', 'object', 'author']);
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
        Schema::dropIfExists('rel_risk_rates');
    }
}
