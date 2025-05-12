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
            'conversation_id' => 'required|integer',
            'message' => 'required|string|max:200',
        ]);

        $receiver_id = $request->receiver_id;
        $sender_id = $request->sender_id;
        $content = $request->message;
        $conversation_id = $request->conversation_id;
        

        DB::beginTransaction();
        try {
            /*
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
            */
                $messages = message::create([
                    'conversation_id' =>  $conversation_id,
                    'sender_id' => $sender_id,
                    'receiver_id' => $receiver_id,
                    'content' => $content,
                ]);

                // Cập nhật last_message
                DB::table('conversations')
                ->where('conversation_id', $conversation_id)
                ->update([
                    'late_message' => $content,
                    'updated_at' => now(),
                ]);
                
            
            
            //Tạo bảng purchase_order
            DB::commit();
            event(new chat ($messages->conversation_id,$content,$receiver_id));
            return response()->json(['msg'=>'event has been fired']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'lỗi']);
        }

        
        
        
    }

    public function chat()
    {
        $currentUserId = Auth::user()->user_id;

        $friends = DB::table('friend_requests as fr')
            ->join('users as u', function ($join) use ($currentUserId) {
                $join->on('fr.sender_id', '=', 'u.user_id')
                    ->where('fr.receiver_id', '=', $currentUserId)
                    ->orOn('fr.receiver_id', '=', 'u.user_id')
                    ->where('fr.sender_id', '=', $currentUserId);
            })
            ->join('conversations as c', 'fr.conversation_id', '=', 'c.conversation_id')
            ->where('fr.status', '=', 'accepted')
            ->select(
                'u.user_id',
                'u.full_name',
                'u.img',
                'fr.conversation_id',
                'c.late_message',
                'c.updated_at',
            )
            ->get();

        return view('chat',compact('friends'));
    }

    public function showMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|integer|max:50'
        ]);
        $messages = message::where('conversation_id',$request->conversation_id)->paginate(10);
        if ($messages) {
            return response()->json([
                'success' => true,
                'messages' => $messages
            ]);
        }
        
    }
}
