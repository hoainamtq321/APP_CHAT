<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('messages_id');
            $table->unsignedInteger('conversation_id'); // Foreign Key
            $table->unsignedInteger('sender_id'); // Foreign Key
            $table->unsignedInteger('receiver_id'); // Foreign Key
            $table->text('msg'); // Nội dung tin nhắn
            $table->timestamp('created_at')->useCurrent(); // Thời gian gửi tin nhắn

            // khoá ngoại
            $table->foreign('conversation_id')->references('conversation_id')->on('conversations')->onDelete('cascade');
            $table->foreign('sender_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('user_id')->on('users')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
