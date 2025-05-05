<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/myStyles.css'])
    <title>Bootstrap Example</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
  </head>
  <body>
    <div class="container">
      <span class="user_id" hidden>{{ Auth::user()->user_id }}</span>
      <div class="content h-100 d-flex">
        
        <div class="mailbox h-100 w-400px">
          <div class="info">
              <div class="letters-item_avatar">
                <img src="img/{{Auth::user()->avatar}}" alt="" srcset="">
              </div>

                <h4>Tên người dùng:{{ Auth::user()->name }}</h4>

          </div>
          <div class="letters">

            @foreach ($users as $user)
            <div class="letters-item">
              <div class="letters-item_avatar">
                <img src="img/{{$user->avatar}}" alt="" srcset="">
              </div>
              <div class="letters-item_info">
                <span class="user_id" hidden>{{ $user->user_id }}</span>
                <h4 >{{$user->name}}</h4>
                <!-- <p>Trạng thái:</p> -->
              </div>
            </div>
            @endforeach

          </div>
          

        </div>
        <!--Hiển thị tin nhắn-->
        <div class="conversation h-100 ">
          <div class="conversation-head d-flex" id="conversation-head">
            <div class="message-avatar">
              <img src="img/default.jpg" alt="" srcset="">
            </div>
            <div class="message-info">
              <h2 id="conversation-head_name">{{ Auth::user()->name }}</h2>
              <!-- <p>Trạng thái:</p> -->
            </div>
          </div>

          <div class="conversation-body" id="conversation-body">
            <div id="loading-messages" style="display: none; text-align: center; padding: 10px;">
              <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Loading...</span>
              </div>
            </div>
          </div>

          <div class="inputMess">
            <div id="loading-messages" style="display: none; text-align: center; padding: 10px;">
              <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Loading...</span>
              </div>
            </div>
            <input type="text" id="inputMsg" name="inputMsg">
            <button type="button" onclick="sendMail()">Gửi</button>
          </div>
        </div>
        <!--End Hiển thị tin nhắn-->
      </div>
    </div>
  @vite('resources/js/app.js')
  <script src="/js/app_chat.js"></script>
  <script>
    /*Xử lý nhắn tin*/

    // mặc định mở tin nhắn một người bạn nhắn gần nhất
    var myFriend = {
      user_id: null,
      name: "",
      avatar:"",
      last_message_id:null, // Lấy ID Tin nhắn cuối cùng
      conversation_id:null, //Lấy ID cuộc hội thoại
    };

    var firstFriend = document.querySelectorAll('.letters-item'); // lấy thông tin người đầu tiên
    var Recipient_name = document.getElementById("conversation-head_name");//Element hiển thị tên người nhắn tin
    var avatar_user = document.querySelector('.message-avatar img');//Element hiển thị ảnh
    var bodyMess = document.getElementById("conversation-body");//Element hiển thị tin nhắn

    var user1_id = {{ Auth::user()->user_id }};


      function showMess()
      {
        $.ajax({
            headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}'},
            url: "{{ route('broadcast.showMessage') }}",  // URL của route xử lý request
            type: "GET",
            data: { 
              user1_id:user1_id,
              user2_id:myFriend.user_id,
            },
            success: function (response) {
              $("#conversation-body").html('');
              myFriend.conversation_id =  response.messages[0].conversation_id
              myFriend.last_message_id =  response.messages[0].messages_id;
              response.messages.forEach(function (msg) {

                if(msg.sender_id == {{ Auth::user()->user_id }})
                {
                  $("#conversation-body").append(
                    `
                    <div class="message">
                      <div class="message-avatar">
                        <img src="img/{{Auth::user()->avatar}}" alt="" srcset="">
                       </div>
                      <div class="message-content">
                        <p>${msg.msg}</p>
                      </div>
                    </div>
                    `
                  );
                }
                else
                {
                  $("#conversation-body").append(
                    `
                    <div class="message justify-content_end">
                      <div class="message-content">
                        <p>${msg.msg}</p>
                      </div>
                      <div class="message-avatar">
                        <img src="${myFriend.avatar}" alt="" srcset="">
                      </div>
                    </div>
                    `
                  );
                }
              });
              scrollToBottom();
            }
        });
      }

    // lưu thông tin 
    function getInfo(input)
    {
      myFriend.user_id = input.getElementsByTagName('span')[0].textContent;
      myFriend.name = input.getElementsByTagName('h4')[0].textContent;
      myFriend.avatar = input.querySelector('.letters-item_avatar img').getAttribute('src');
      avatar_user.src = myFriend.avatar;
      Recipient_name.textContent = myFriend.name;

      showMess();

    }
    getInfo(firstFriend[0]);

    // console.log(myFriend);

    // Chọn người bạn để bắt đầu và hiển thị tin nhắn
    document.querySelectorAll('.letters-item').forEach(item => {
            item.addEventListener('click', function() {
              getInfo(item);// Hiển thị tin nhắn cũ
              let Recipient_name = document.getElementById("conversation-head_name");
              Recipient_name.textContent = myFriend.name;
              console.log(myFriend);
            });
    });

    /*End Xử lý nhắn tin*/

    /*Xử lý gửi tin nhắn Begin*/
    function sendMail(){
      let msg = $("#inputMsg").val();
      $("#inputMsg").val("");
      console.log(msg);
      $.ajax({
            headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}'},
            type:'POST',
            data:{
                message:msg,
                myFriend:myFriend,
            },
            url: "{{ route('broadcast.sendMail') }}",
            success: function(response){
                console.log(response,"abc");
                $("#conversation-body").append(
                    `
                    <div class="message">
                      <div class="message-avatar">
                        <img src="img/{{Auth::user()->avatar}}" alt="" srcset="">
                       </div>
                      <div class="message-content">
                        <p>${response.message}</p>
                      </div>
                    </div>
                    `
                );
                scrollToBottom();
            }
        })
    };

    setTimeout(() => {
        Echo.channel('channel_Message').listen('chat',(data)=>{

            if({{ Auth::user()->user_id }}==data.username)
            {
            $("#conversation-body").append(
                    `
                    <div class="message justify-content_end">
                      <div class="message-content">
                        <p>${data.message}</p>
                      </div>
                      <div class="message-avatar">
                        <img src="${myFriend.avatar}" alt="" srcset="">
                      </div>
                    </div>
                    `
                );
                scrollToBottom();
            }
        })
    }, 100);

    /*Xử lý gửi tin nhắn End*/
  </script>
  <script>

// Lazy Loading
let loading = false;  // Biến kiểm tra trạng thái đang tải dữ liệu
let lastMessageId = 0;  // ID của tin nhắn gần nhất đã tải, cần truyền theo API

// Lắng nghe sự kiện cuộn của phần tử chứa tin nhắn
$("#conversation-body").scroll(function() {
    // Kiểm tra nếu người dùng đã cuộn gần đầu
    if ($("#conversation-body").scrollTop() === 0 && !loading) {
        loading = true;  // Đánh dấu là đang tải dữ liệu

        // Gọi hàm để tải thêm tin nhắn cũ
        loadMoreMessages();
    }
});
// Hàm tải thêm tin nhắn cũ
function loadMoreMessages() {
  const oldScrollHeight = $("#conversation-body")[0].scrollHeight; // lưu chiều cao trức khi cuận
    $.ajax({
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        url: "{{ route('broadcast.showOldMess') }}",  // URL của route xử lý request
        type: "GET",
        data: { 
            conversation_id: myFriend.conversation_id, // Truyền ID cuộc hội thoại
            last_message_id: myFriend.last_message_id,  // Truyền ID của tin nhắn gần nhất đã tải
        },
        success: function (response) {

            if (response.messages.length > 0) {
                // Thêm các tin nhắn cũ vào đầu danh sách
                console.log(response.messages);
                response.messages.forEach(function (msg) {
                    // Kiểm tra tin nhắn từ người gửi
                    if(msg.sender_id == {{ Auth::user()->user_id }}) {
                        $("#conversation-body").prepend(
                            `
                            <div class="message">
                                <div class="message-avatar">
                                    <img src="img/{{Auth::user()->avatar}}" alt="" srcset="">
                                </div>
                                <div class="message-content">
                                    <p>${msg.msg}</p>
                                </div>
                            </div>
                            `
                        );
                    } else {
                        $("#conversation-body").prepend(
                            `
                            <div class="message justify-content_end">
                                <div class="message-content">
                                    <p>${msg.msg}</p>
                                </div>
                                <div class="message-avatar">
                                    <img src="${myFriend.avatar}" alt="" srcset="">
                                </div>
                            </div>
                            `
                        );
                    }
                    // Cập nhật ID của tin nhắn gần nhất
                    myFriend.last_message_id = msg.messages_id;
                });

                // Cuộn lên trên để giữ trạng thái cuộn của người dùng
                //$("#conversation-body").scrollTop($("#conversation-body")[0].scrollHeight);
                
                
                const newScrollHeight = $("#conversation-body")[0].scrollHeight;
                $("#conversation-body")[0].scrollTop = newScrollHeight - oldScrollHeight;
            }
            loading = false;  // Đánh dấu kết thúc tải dữ liệu
        },
        error: function () {
            loading = false;  // Đánh dấu kết thúc tải dữ liệu khi có lỗi
        }
    });
}
  </script>
  </body>
</html>