<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCourseTableAddSlugAndUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('slug', 120)->nullable()->default(null);
            $table->unsignedBigInteger('user_id')->nullable()->default(null);

            $table->foreign('user_id')
                -> references('id')
                -> on('users')
                -> onUpdate('cascade')
                -> onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('slug');
            $table->dropColumn('user_id');
        });
    }
}
