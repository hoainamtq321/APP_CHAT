<?php

namespace App\Http\Controllers;

use App\Models\conversation;
use App\Models\conversation_member;
use App\Models\friend_request;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FriendController extends Controller
{
    public  function searchFriend(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:200'
        ]);
        
        $userId = Auth::user()->user_id;
        $userName = $request->full_name;
        if($userName == 'start')
        {
            $users = DB::table('users')
            ->leftJoin('friend_requests', function ($join) use ($userId) {
                $join->on('users.user_id', '=', 'friend_requests.sender_id')
                    ->where('friend_requests.receiver_id', '=', $userId)
                    ->orOn('users.user_id', '=', 'friend_requests.receiver_id')
                    ->where('friend_requests.sender_id', '=', $userId);
            })
            ->where('users.user_id', '!=', $userId)
            ->select(
                'users.user_id',
                'users.full_name',
                'users.img',
                DB::raw('IF(friend_requests.id IS NULL, 0, 1) as friend')
            )
            ->paginate(20); // Hoặc dùng ->cursorPaginate(20) nếu dữ liệu rất lớn

        }
        else
        {
            $users = DB::table('users')
                ->leftJoin('friend_requests', function ($join) use ($userId) {
                    $join->on('users.user_id', '=', 'friend_requests.sender_id')
                        ->where('friend_requests.receiver_id', '=', $userId)
                        ->orOn('users.user_id', '=', 'friend_requests.receiver_id')
                        ->where('friend_requests.sender_id', '=', $userId);
                })
                ->where('users.user_id', '!=', $userId)
                ->when($userName, function ($query) use ($userName) {
                    $query->where('users.full_name', 'LIKE', "%$userName%");
                })
                ->select(
                    'users.user_id',
                    'users.full_name',
                    'users.img',
                    DB::raw('IF(friend_requests.id IS NULL, 0, 1) as friend')
                )
                ->paginate(20); // Hoặc dùng ->cursorPaginate(20) nếu dữ liệu rất lớn
        }

        return response()->json($users);
    }

    public function sendRequest(Request $request){
        $request->validate([
            'user_id' => 'required|integer|max:55'
        ]);

        $sender = Auth::user()->user_id;
        $receiver = $request->user_id;
        $name = "Cuộc trò chuyện của " . $sender . " và ". $receiver;

        // Kiểm tra nếu trong quá trình truy xuất sẩy ra lỗi sẽ reset
        DB::beginTransaction();
            try {
                $conversations = conversation::create([
                    'name'=>$name
                ]);

                // Kiểm tra xem đã có dòng friend_requests giữa 2 người chưa (dù ai là sender hay receiver)
                $request = DB::table('friend_requests')
                    ->where(function ($query) use ($sender, $receiver) {
                        $query->where('sender_id', $sender)
                            ->where('receiver_id', $receiver);
                    })
                    ->orWhere(function ($query) use ($sender, $receiver) {
                        $query->where('sender_id', $receiver)
                            ->where('receiver_id', $sender);
                    })
                    ->first();
                if ($request) {
                    // Nếu đã tồn tại thì huỷ kết bạn (xoá dòng đó)
                    dd("ý ý Bug");
                } else {
                    // Nếu chưa có thì tạo yêu cầu kết bạn
                    $friends = friend_request::create([
                        'sender_id' => $sender ,
                        'receiver_id' => $receiver ,
                        'conversation_id' => $conversations->conversation_id,
                    ]);
                    /* Nhóm cuộc trò truyện của 2 users
                    DB::table('conversation_members')->insert([
                        ['conversation_id' => $conversations->conversation_id, 'user_id' => $sender],
                        ['conversation_id' => $conversations->conversation_id, 'user_id' => $receiver],
                    ]);
                    */

                }
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Gửi yêu cầu kết bạn']);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Lỗi tạo đơn hàng']);
            }

    }

    public function acceptRequest(Request $request){
        $request->validate([
            'user_id' => 'required|integer|max:55'
        ]);

        $sender = $request->user_id;
        $receiver = Auth::user()->user_id;
        // Kiểm tra xem đã có dòng friend_requests giữa 2 người chưa (dù ai là sender hay receiver)
        $friend = DB::table('friend_requests')
            ->where(function ($query) use ($sender, $receiver) {
                $query->where('sender_id', $sender)
                    ->where('receiver_id', $receiver);
            })
            ->orWhere(function ($query) use ($sender, $receiver) {
                $query->where('sender_id', $receiver)
                    ->where('receiver_id', $sender);
            })
            ->first();
        
        if ($friend) {
            DB::table('friend_requests')
                ->where('id', $friend->id)
                ->update(['status' => 'accepted']);
            
            DB::table('conversation_members')->insert([
                        ['conversation_id' => $friend->conversation_id, 'user_id' => $friend->sender_id, 'created_at' => now(),'updated_at' => now()],
                        ['conversation_id' => $friend->conversation_id, 'user_id' => $friend->receiver_id, 'created_at' => now(), 'updated_at' => now()],
                    ]);
        }
        return response()->json(['success' => true, 'message' => 'Chấp nhận kết bạn']);
    }

    public function refuseRequest(Request $request){
        $request->validate([
            'user_id' => 'required|integer|max:55'
        ]);
        $sender = $request->user_id;
        $receiver = Auth::user()->user_id;

        DB::table('friend_requests')
            ->where(function ($query) use ($sender, $receiver) {
                $query->where('sender_id', $sender)
                    ->where('receiver_id', $receiver);
            })
            ->orWhere(function ($query) use ($sender, $receiver) {
                $query->where('sender_id', $receiver)
                    ->where('receiver_id', $sender);
            })
            ->delete();
        DB::table('conversation_members')
            ->whereIn('user_id', [$sender, $receiver]) // Tìm các bản ghi có user1 và user2
            ->groupBy('conversation_id') // Nhóm theo conversation_id
            ->havingRaw('COUNT(DISTINCT user_id) = 2') // Đảm bảo chỉ có user1 và user2 (không có thêm người khác)
            ->delete(); // Xóa các bản ghi tương ứng
        return response()->json(['success' => true, 'message' => 'Không chấp nhận kết bạn']);
    }

    public function friendRequest()
    {
        $userId = Auth::user()->user_id;
        $users = DB::table('users')
            ->leftJoin('friend_requests', function ($join) use ($userId) {
                $join->on('users.user_id', '=', 'friend_requests.sender_id')
                    ->where('friend_requests.receiver_id', '=', $userId);
            })
            ->where('friend_requests.status', '=', 'pending')
            ->where('users.user_id', '!=', $userId)
            ->select('users.user_id', 'users.full_name', 'users.img')
            ->paginate(10);
        /*
                    ->where('users.user_id', '!=', $userId)
            ->where('friend_requests.status', '=', 'pending')
            ->where('friend_requests.sender_id', '!=', $userId)
            ->select('users.user_id', 'users.full_name', 'users.img')
            ->paginate(10);
        */
        return response()->json($users);
    }
}
