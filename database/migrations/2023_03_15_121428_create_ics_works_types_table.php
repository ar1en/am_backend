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
        Schema::create('ent_ics_works_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('name');
            $table->uuid('author');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('author')->references('id')->on('ent_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ent_ics_works_types');
    }
};
