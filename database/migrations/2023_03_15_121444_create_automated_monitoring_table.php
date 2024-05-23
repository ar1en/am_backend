<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutomatedMonitoringTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('ent_automated_monitoring', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('department');
            $table->text('desc')->nullable();
            #$table->uuid('status');
            #$table->timestamp('statusDate');
            $table->uuid('author');
            $table->timestamps();
            $table->softDeletes();
            #$table->timestamp('createdAt');
            #$table->timestamp('updatedAt');

            $table->foreign('department')->references('id')->on('ent_departments');
            $table->foreign('author')->references('id')->on('ent_users');
            $table->index(['department', 'author']);
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
        Schema::dropIfExists('ent_automated_monitoring');
    }
}
