<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRisksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('ent_risks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('sasId')->unique();
            $table->string('bpmId')->unique();
            $table->text('name');
            $table->uuid('type');
            $table->string('code')->nullable();
            $table->timestamp('validFrom');
            $table->timestamp('validUntil')->nullable();
            $table->uuid('author');
            $table->timestamps();
            $table->softDeletes();
            #$table->timestamp('createdAt');
            #$table->timestamp('updatedAt');

            $table->foreign('type')->references('id')->on('ent_risks_types');
            $table->foreign('author')->references('id')->on('ent_users');
            $table->index(['type', 'author']);
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
        Schema::dropIfExists('ent_risks');
    }
}
