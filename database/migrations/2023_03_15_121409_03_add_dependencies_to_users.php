<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ent_users', function (Blueprint $table) {

            #$table->foreign('vacancy')->references('id')->on('vacancies');
            $table->foreign('author')->references('id')->on('ent_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ent_users', function (Blueprint $table) {

            #$table->dropForeign(['vacancy']);
            $table->dropForeign(['author']);
        });
    }
};
