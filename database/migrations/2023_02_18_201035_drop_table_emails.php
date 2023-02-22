<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTableEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('sent_emails');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('sent_emails', function (Blueprint $table) {
            $table->id();
            $table->string('subject', 210);
            $table->text('content');
            $table->string('attachments_dir',191);
            $table->unsignedBigInteger('sender_id')->nullable()->default(null);
            $table->foreign('sender_id')
                -> references('id')
                -> on('users')
                -> onUpdate('cascade')
                -> onDelete('cascade');
            $table->unsignedBigInteger('course_id')->nullable()->default(null);
            $table->foreign('course_id')
                -> references('id')
                -> on('courses')
                -> onUpdate('cascade')
                -> onDelete('cascade');
            $table->text('recipients_emails');
            $table->timestamps();
        });
    }
}
