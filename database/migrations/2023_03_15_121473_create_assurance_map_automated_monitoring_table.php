<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssuranceMapAutomatedMonitoringTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('rel_assurance_map_automated_monitoring', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assuranceMap');
            $table->uuid('automatedMonitoring');
            $table->uuid('author');
            $table->timestamps();
            $table->softDeletes();
            #$table->timestamp('createdAt');
            #$table->timestamp('updatedAt');

            $table->foreign('assuranceMap')->references('id')->on('ent_assurance_maps');
            $table->foreign('automatedMonitoring')->references('id')->on('ent_automated_monitoring');
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
        Schema::dropIfExists('rel_assurance_map_automated_monitoring');
    }
}
