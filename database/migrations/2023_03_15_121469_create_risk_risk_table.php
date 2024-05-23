<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiskRiskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('rel_risk_risk', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('risk1');
            $table->uuid('risk2');
            $table->uuid('author');
            $table->timestamps();
            $table->softDeletes();
            #$table->timestamp('createdAt');
            #$table->timestamp('updatedAt');

            $table->foreign('risk1')->references('id')->on('ent_risks');
            $table->foreign('risk2')->references('id')->on('ent_risks');
            $table->foreign('author')->references('id')->on('ent_users');
            $table->index(['risk2', 'risk1', 'author']);
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
        Schema::dropIfExists('rel_risk_risk');
    }
}
