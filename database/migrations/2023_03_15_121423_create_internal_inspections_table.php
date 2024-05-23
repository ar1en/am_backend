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

        Schema::create('ent_internal_inspections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('department');
            $table->text('desc');
            $table->uuid('object');
            #$table->uuid('status');
            #$table->uuid('statusDate');
            $table->uuid('author');
            $table->timestamps();
            $table->softDeletes();
            #$table->timestamp('createdAt');
            #$table->timestamp('updatedAt');

            $table->foreign('department')->references('id')->on('ent_departments');
            $table->foreign('author')->references('id')->on('ent_users');
            $table->index(['department', 'object', 'author']);
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
        Schema::dropIfExists('ent_internal_inspections');
    }
};
