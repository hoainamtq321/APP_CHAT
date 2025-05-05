<?php

namespace App\Http\Controllers;

use App\Events\chat;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function broadcastChat()
    {
        event(new chat('yasua','this is message'));
        return response()->json(['msg'=>'event has been fired!']);
    }

    public function index()
    {
        return view('home');
    }

    public function sendMail(Request $request)
    {
        
        $request->validate([
            'message'=>'required',
            'myFriend'=>'required'
        ]);
        // dd($request->myFriend['user_id']);

        $user1_id = Auth::id(); // Lấy ID user đang đăng nhập
        $user2_id = $request->myFriend['user_id']; // ID người muốn mở hộp thoại với

        // dd($user1_id."".$user2_id);

        // Kiểm tra xem hộp thoại đã tồn tại chưa
        $conversation = Conversation::where(function ($query) use ($user1_id, $user2_id) {
            $query->where('user1_id', $user1_id)->where('user2_id', $user2_id);
        })->orWhere(function ($query) use ($user1_id, $user2_id) {
            $query->where('user1_id', $user2_id)->where('user2_id', $user1_id);
        })->first();
            // Nếu chưa có hộp thoại, tạo mới
        if (!$conversation) {
            $conversation = Conversation::create([
                'user1_id' => $user1_id,
                'user2_id' => $user2_id,
            ]);
        }

        // lưu tin nhắn
        $sender_id = Auth::id();
        $receiver_id = $request->myFriend['user_id']; // ID người muốn mở hộp thoại với
        $msg = $request->message;

        // Tìm hộp thoại giữa hai user
        $conversation = Conversation::where(function ($query) use ($sender_id, $receiver_id) {
            $query->where('user1_id', $sender_id)->where('user2_id', $receiver_id);
        })->orWhere(function ($query) use ($sender_id, $receiver_id) {
            $query->where('user1_id', $receiver_id)->where('user2_id', $sender_id);
        })->first();

        // Tạo tin nhắn mới
        $message = Message::create([
            'conversation_id' => $conversation->conversation_id,
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'msg' => $msg
        ]);

        // Cập nhật tin nhắn cuối cùng trong hộp thoại
        $conversation->update(['last_message_id' => $message->messages_id]);
        event(new chat($receiver_id,$request->message));
        return response()->json(['msg'=>'event has been fired!','message'=>$request->message,'user_id'=>$receiver_id]);
    }
    
    public function showMessage(Request $request)
    {

        $request->validate([
            'user1_id'=>'required',
            'user2_id'=>'required'
        ]);
        
        $user1_id = $request->user1_id;
        $user2_id = $request->user2_id;
        // Kiểm tra xem hộp thoại đã tồn tại chưa
        $conversation = Conversation::where(function ($query) use ($user1_id, $user2_id) {
            $query->where('user1_id', $user1_id)->where('user2_id', $user2_id);
        })->orWhere(function ($query) use ($user1_id, $user2_id) {
            $query->where('user1_id', $user2_id)->where('user2_id', $user1_id);
        })->first();

        // Nếu không có hộp thoại, trả về danh sách rỗng
        if (!$conversation) {
            return response()->json(['messages' => []]);
        }

        // Lấy 10 tin nhắn gần nhất của cuộc hội thoại
        $messages = Message::where('conversation_id', $conversation->conversation_id)
        ->orderBy('created_at', 'desc') // Lấy tin mới nhất trước
        ->take(10)
        ->get()
        ->reverse() // Đảo ngược lại cho đúng thứ tự thời gian
        ->values() // Đảm bảo không có key bị đảo
        ->toArray(); // Chuyển Collection thành mảng
        return response()->json(['messages' => $messages]);
    }

    public function showOldMess(Request $request)
    {
        // Kiểm tra tính hợp lệ của request
        $request->validate([
            'conversation_id' => 'required|integer',
            'last_message_id' => 'required|integer', // ID của tin nhắn gần nhất đã tải
        ]);
        
        // Lấy các tham số từ request
        $conversation_id = $request->conversation_id;
        $last_message_id = $request->last_message_id;
    
        // Kiểm tra xem hộp thoại đã tồn tại chưa
        //$conversation = Conversation::where('conversation_id', $conversation_id)->first();
        $conversation = Conversation::find($conversation_id);
    
        // Lấy các tin nhắn cũ hơn dựa trên ID của tin nhắn gần nhất đã tải
        $messages = Message::where('conversation_id', $conversation->conversation_id)
            ->where('messages_id', '<', $last_message_id) // Lọc các tin nhắn cũ hơn tin nhắn gần nhất đã tải
            ->orderBy('created_at', 'asc') // Lấy tin nhắn cũ trước
            ->take(10) // Giới hạn 10 tin nhắn
            ->get()
            ->reverse() // Đảo ngược lại để có thứ tự đúng
            ->values() // Đảm bảo không có key bị đảo
            ->toArray(); // Chuyển Collection thành mảng
    
        return response()->json(['messages' => $messages]);
    }

    public function message()
    {
        $users =  User::where('user_id', '!=', auth()->id())->get();
        return view('message',compact('users'));
    }

    public function conversation()
    {
        $users =  User::where('user_id', '!=', auth()->id())->get();
        return view('conversation',compact('users'));
    }
}
