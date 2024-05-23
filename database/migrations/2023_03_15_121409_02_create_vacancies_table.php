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

        Schema::create('ent_vacancies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('desc')->nullable();
            $table->uuid('type')->nullable();
            #$table->uuid('user');
            $table->uuid('department');
            $table->timestamp('validFrom');
            $table->timestamp('validUntil')->nullable();
            $table->uuid('author');
            $table->timestamps();
            $table->softDeletes();
            #$table->timestamp('createdAt');
            #$table->timestamp('updatedAt');

            $table->foreign('type')->references('id')->on('ent_vacancies_types');
            #$table->foreign('user')->references('id')->on('users');
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('ent_vacancies');
        Schema::enableForeignKeyConstraints();
    }
};
