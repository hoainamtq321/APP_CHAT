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
        Schema::create('friend_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sender_id');
            $table->unsignedInteger('receiver_id');
            $table->unsignedInteger('conversation_id');
            $table->enum('status',['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();

            $table->foreign('conversation_id') // Định nghĩa khoá conversation_id
                ->references('conversation_id') // Cột conversation_id tham chiếu đến cột conversation_id 
                ->on('conversations') // bảng được tham chiếu
                ->onDelete('cascade'); // Nếu bản ghi trong conversation_members xoá thì tất cả bản ghi trong messages có conversation_id cũng xoá theo
            
            $table->foreign('sender_id') 
                ->references('user_id') 
                ->on('users') 
                ->onDelete('cascade');
            
            $table->foreign('receiver_id') 
                ->references('user_id') 
                ->on('users') 
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('friend_requests');
    }
};
