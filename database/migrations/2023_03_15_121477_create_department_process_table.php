<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('rel_department_process', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('department');
            $table->uuid('process');
            $table->text('desk')->nullable();
            $table->boolean('controlFunction');
            $table->text('result')->nullable();
            $table->uuid('author');
            $table->timestamps();
            $table->softDeletes();
            #$table->timestamp('createdAt');
            #$table->timestamp('updatedAt');

            $table->foreign('department')->references('id')->on('ent_departments');
            $table->foreign('process')->references('id')->on('ent_processes');
            $table->foreign('author')->references('id')->on('ent_users');
            $table->index(['department', 'process', 'desk', 'author']);
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
        Schema::dropIfExists('rel_department_process');
    }
}
