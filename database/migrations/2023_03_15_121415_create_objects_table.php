<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('ent_objects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('sasId');
            $table->text('name');
            $table->text('code');
            $table->uuid('supervisingDivision');
            $table->uuid('supervisor');
            $table->timestamp('validFrom');
            $table->timestamp('validUntil')->nullable();
            #$table->uuid('assuranceMap');
            $table->uuid('author');
            $table->timestamps();
            $table->softDeletes();
            #$table->timestamp('createdAt');
            #$table->timestamp('updatedAt');

            $table->foreign('supervisingDivision')->references('id')->on('ent_departments');
            $table->foreign('supervisor')->references('id')->on('ent_users');
            $table->foreign('author')->references('id')->on('ent_users');
            $table->index(['supervisingDivision', 'supervisor', 'author']);
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
        Schema::dropIfExists('ent_objects');
    }
}
