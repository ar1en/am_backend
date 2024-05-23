<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollegiateBodyDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('rel_collegiate_body_document', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('collegiateBody');
            $table->uuid('document');
            $table->uuid('author');
            $table->timestamps();
            $table->softDeletes();
            #$table->timestamp('createdaAt');
            #$table->timestamp('updatedAt');

            $table->foreign('collegiateBody')->references('id')->on('ent_collegiate_bodies');
            $table->foreign('document')->references('id')->on('ent_documents');
            $table->foreign('author')->references('id')->on('ent_users');
            $table->index(['collegiateBody', 'document', 'author']);
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
        Schema::dropIfExists('rel_collegiate_body_document');
    }
}
