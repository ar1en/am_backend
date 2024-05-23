<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssuranceMapDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('rel_assurance_map_document', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assuranceMap');
            $table->uuid('document');
            $table->uuid('author');
            $table->timestamps();
            $table->softDeletes();
            #$table->timestamp('createdAt');
            #$table->timestamp('updatedAt');

            $table->foreign('assuranceMap')->references('id')->on('ent_assurance_maps');
            $table->foreign('document')->references('id')->on('ent_documents');
            $table->foreign('author')->references('id')->on('ent_users');
            $table->index(['author', 'document', 'assuranceMap']);
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
        Schema::dropIfExists('rel_assurance_map_document');
    }
}
