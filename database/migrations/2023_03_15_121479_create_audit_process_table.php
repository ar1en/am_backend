<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('rel_audit_process', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('audit');
            $table->uuid('process');
            $table->uuid('author');
            $table->timestamps();
            $table->softDeletes();
            #$table->timestamp('createdAt');
            #$table->timestamp('updatedAt');

            $table->foreign('audit')->references('id')->on('ent_audits');
            $table->foreign('process')->references('id')->on('ent_processes');
            $table->foreign('author')->references('id')->on('ent_users');
            $table->index(['audit', 'process', 'author']);
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
        Schema::dropIfExists('rel_audit_process');
    }
}
