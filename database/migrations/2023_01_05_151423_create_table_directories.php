<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDirectories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('slug', 120);
            $table->string('description',500)->nullable()->default(null);
            $table->boolean('active')->nullable(false)->default(true);
            $table->string('disk')->nullable(false)->default('gallery');
            $table->unsignedBigInteger('user_id')->nullable()->default(null);
            $table->foreign('user_id')
                -> references('id')
                -> on('users')
                -> onUpdate('cascade')
                -> onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('directories');
    }
}
