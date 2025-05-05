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
          <div class="searchBox">
            <input type="search">
            <button type="button">Tìm kiếm</button>
          </div>

          <div class="letters">

            @foreach ($users as $user)
            <div class="letters-item">
              <div class="letters-item_avatar">
                <img src="img/default.jpg" alt="" srcset="">
              </div>
              <div class="letters-item_info">
                <span class="user_id" hidden>{{ $user->user_id }}</span>
                <h4 >{{$user->name}}</h4>
                <p>Tin nhắn cuối cùng</p>
              </div>
            </div>
            @endforeach

          </div>
          

        </div>

        <div class="conversation h-100 ">
          <div class="conversation-head" id="conversation-head">
            <h2 id="conversation-head_name">{{ Auth::user()->name }}</h2>

            <p>Trạng thái: say</p>
          </div>

          <div class="conversation-body" id="conversation-body">
          </div>

          <div class="inputMess">
            <input type="text" id="inputMsg" name="inputMsg">
            <button type="button" onclick="sendMail()">Gửi</button>
          </div>
        </div>

      </div>
    </div>
  @vite('resources/js/app.js')
  <script src="/js/app_chat.js"></script>
  <script>
    /*Xử lý nhắn tin*/

    // mặc định mở tin nhắn một người bạn nhắn gần nhất
    var myFriend = {
      user_id: null,
      name: ""
    };

    var firstFriend = document.querySelectorAll('.letters-item'); // lấy thông tin người đầu tiên
    var headerMess = document.getElementById("conversation-head_name");// hiển thị tên người nhắn tin
    var bodyMess = document.getElementById("conversation-body");

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
              response.messages.forEach(function (msg) {

                if(msg.sender_id == {{ Auth::user()->user_id }})
                {
                  $("#conversation-body").append(
                    `
                    <div class="message">
                      <div class="message-avatar">
                        <img src="img/default.jpg" alt="" srcset="">
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
                        <img src="img/default.jpg" alt="" srcset="">
                      </div>
                    </div>
                    `
                  );
                }
              });
            }
        });
      }

    // lưu thông tin 
    function getInfo(input)
    {
      myFriend.user_id = input.getElementsByTagName('span')[0].textContent;
      myFriend.name = input.getElementsByTagName('h4')[0].textContent;
      headerMess.textContent = myFriend.name;

      showMess();

    }
    getInfo(firstFriend[0]);

    // console.log(myFriend);

    // Chọn người bạn để bắt đầu
    document.querySelectorAll('.letters-item').forEach(item => {
            item.addEventListener('click', function() {
              getInfo(item);
              let headerMess = document.getElementById("conversation-head_name");
              headerMess.textContent = myFriend.name;
              console.log(myFriend);
            });
    });

    /*End Xử lý nhắn tin*/

    /*Xử lý gửi tin nhắn Begin*/
    function sendMail(){
      let msg = $("#inputMsg").val();
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
                        <img src="img/default.jpg" alt="" srcset="">
                       </div>
                      <div class="message-content">
                        <p>${response.message}</p>
                      </div>
                    </div>
                    `
                );
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
                        <img src="img/default.jpg" alt="" srcset="">
                      </div>
                    </div>
                    `
                );
            }
        })
    }, 100);

    /*Xử lý gửi tin nhắn End*/
  </script>
  </body>
</html>