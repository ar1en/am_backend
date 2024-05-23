<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssuranceMapSelfRatingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('rel_assurance_map_self_rating', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assuranceMap');
            $table->uuid('selfRating');
            $table->uuid('author');
            $table->timestamps();
            $table->softDeletes();
            #$table->timestamp('createdAt');
            #$table->timestamp('updatedAt');

            $table->foreign('assuranceMap')->references('id')->on('ent_assurance_maps');
            $table->foreign('author')->references('id')->on('ent_users');
            $table->index(['assuranceMap', 'selfRating', 'author']);
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
        Schema::dropIfExists('rel_assurance_map_self_rating');
    }
}
