<?php

namespace App\Http\Controllers;

use App\Events\chat;
use App\Models\conversation;
use App\Models\message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function broadcastChat(Request $request){
        
        $request->validate([
            'receiver_id' => 'required|integer',
            'sender_id' => 'required|integer',
            'message' => 'required|string|max:200',
        ]);
        
        $receiver_id = $request->receiver_id;
        $sender_id = $request->sender_id;
        $message = $request->message;
        

        DB::beginTransaction();
        try {
            // Kiểm tra đã có cuộc trò chuyện chưa
            $exists = DB::table('conversations')
                    ->where(function($query) use ($receiver_id, $sender_id) {
                        $query->where('user1_id', $receiver_id)
                            ->where('user2_id', $sender_id);
                    })
                    ->orWhere(function($query) use ($receiver_id, $sender_id) {
                        $query->where('user1_id', $sender_id)
                            ->where('user2_id', $receiver_id);
                    })
                    ->first();
                    
            if($exists)
            {
                $messages = message::create([
                    'conversation_id' =>  $exists->conversation_id,
                    'sender_id' => $sender_id,
                    'receiver_id' => $receiver_id,
                    'message' => $message,
                    'is_read'=> false,
                ]);

                // Cập nhật last_message
                DB::table('conversations')
                ->where('conversation_id', $exists->conversation_id)
                ->update([
                    'last_message' => $message,
                    'updated_at' => now(),
                ]);
                
            }
            else
            {
                $conversations = conversation::create([
                    'user1_id' => $sender_id,
                    'user2_id' => $receiver_id,
                    'last_message' => ""
                ]);
            }
            //Tạo bảng purchase_order
            DB::commit();
            event(new chat ($sender_id,$message));
            return response()->json(['msg'=>'event has been fired']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'lỗi']);
        }

        
        
        
    }

    public function chat()
    {
        $currentUserId = Auth::user()->user_id;

        $users = DB::table('conversations')
            ->where('user1_id', $currentUserId)
            ->orWhere('user2_id', $currentUserId)
            ->join('users', function ($join) use ($currentUserId) {
                $join->on('users.user_id', '=', DB::raw("IF(conversations.user1_id = $currentUserId, conversations.user2_id, conversations.user1_id)"));
            })
            ->select(
                'users.user_id',
                'users.full_name',
                'users.img',
                'conversations.conversation_id',
                'conversations.last_message',
                'conversations.updated_at'
            )
            ->orderByDesc('conversations.updated_at') // Sắp xếp theo thời gian mới nhất
            ->get();
        return view('chat',compact('users'));
    }

    public function showMessage(Request $request)
    {
        $conversation = conversation::find($request->conversation_id);
        $messages = message::where('conversation_id',$request->conversation_id)->get();
        if ($conversation) {
            $messages = \App\Models\Message::where('conversation_id', $conversation->conversation_id)->get();
            return response()->json([
                'success' => true,
                'messages' => $messages
            ]);
        }
        
    }
}
