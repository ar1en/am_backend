<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('ent_logins', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user')->constrained('ent_users');
            $table->string('login');
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->foreignUuid('author')->constrained('ent_users');
            #$table->timestamp('createdAt');
            #$table->timestamp('updatedAt');
            $table->timestamp('validFrom');
            $table->timestamp('validUntil')->nullable();

            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('ent_logins');
    }
}
