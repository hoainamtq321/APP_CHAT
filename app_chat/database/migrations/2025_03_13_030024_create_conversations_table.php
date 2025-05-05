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
        Schema::create('conversations', function (Blueprint $table) {
            $table->increments('conversation_id'); // BIGINT Primary Key, Auto Increment
            $table->unsignedInteger('user1_id'); // ID người dùng thứ nhất
            $table->unsignedInteger('user2_id'); // ID người dùng thứ hai
            $table->unsignedInteger('last_message_id')->nullable(); // ID tin nhắn cuối cùng
            $table->timestamp('created_at')->useCurrent(); // Thời gian tạo cuộc trò chuyện

            // Define foreign keys
            $table->foreign('user1_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('user2_id')->references('user_id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversations');
    }
};
