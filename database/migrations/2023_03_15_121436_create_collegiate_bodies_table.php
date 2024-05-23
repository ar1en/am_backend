<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollegiateBodiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('ent_collegiate_bodies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('type');
            $table->text('desc')->nullable();
            $table->uuid('sourceDepartment');
            #$table->uuid('status');
            #$table->time('statusDate');
            $table->uuid('author');
            $table->timestamps();
            $table->softDeletes();
            #$table->timestamp('createdAt');
            #$table->timestamp('updatedAt');

            $table->foreign('type')->references('id')->on('ent_collegiate_bodies_types');
            $table->foreign('sourceDepartment')->references('id')->on('ent_departments');
            $table->foreign('author')->references('id')->on('ent_users');
            $table->index(['sourceDepartment', 'author']);
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
        Schema::dropIfExists('ent_collegiate_bodies');
    }
}
