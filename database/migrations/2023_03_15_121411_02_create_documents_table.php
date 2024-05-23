<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('ent_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('name');
            $table->uuid('type');
            $table->timestamp('validFrom');
            $table->timestamp('validUntil')->nullable();
            $table->uuid('author');
            $table->timestamps();
            $table->softDeletes();
            #$table->timestamp('createdAt');
            #$table->timestamp('updatedAt');

            $table->foreign('type')->references('id')->on('ent_documents_types');
            $table->foreign('author')->references('id')->on('ent_users');
            $table->index('author');
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
        Schema::dropIfExists('ent_documents');
    }
};
