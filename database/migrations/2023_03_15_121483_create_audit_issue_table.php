<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditIssueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('rel_audit_issue', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('audit');
            $table->uuid('issue');
            $table->uuid('author');
            $table->timestamps();
            $table->softDeletes();
            #$table->timestamp('createdAt');
            #$table->timestamp('updatedAt');

            $table->foreign('audit')->references('id')->on('ent_audits');
            $table->foreign('issue')->references('id')->on('ent_issues');
            $table->foreign('author')->references('id')->on('ent_users');
            $table->index(['audit', 'issue', 'author']);
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
        Schema::dropIfExists('rel_audit_issue');
    }
}
