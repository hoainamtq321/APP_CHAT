<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Broadcast</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.12.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" integrity="sha256-mmgLkCYLUQbXn0B1SRqzHar6dCnv9oZFPEC1g1cwlkk=" crossorigin="anonymous" />
    <!--Ngày tháng năm-->
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/isToday.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/isYesterday.js"></script>


</head>
<body>
<div class="inner_body">
        <section class="create_projectnw">
            <div class="container">
                <div class="row justify-content-center">

                    <div class="col-lg-12 col-sm-12">
                        <div class="left_menubx">
                            <div class="frame">
                                
                                <div id="sidepanel" class="sidepanel">
                                                                        <div id="profile" data-id="{{Auth::user()->user_id}}">
                                                                            <div class="icon-badge-container pending-requests">
                                                                                
                                                                                <i class="far fa-user icon-badge-icon"></i>
                                                                                <!--Hiện thị kết bạn gì người dùng có bạn bè gửi lời mời-->
                                                                                <div class="icon-badge">6</div>
                                                                            </div>
                                                                            <div class="wrap">
                                                                                <img id="profile-img" src="{{Auth::user()->img}}" class="online" alt="" />
                                                                                <p id="profile-fullName">{{Auth::user()->full_name}}</p>
                                                                                
                                                                                <div id="status-options">
                                                                                    <ul>
                                                                                        <li id="status-online" class="active"><span class="status-circle"></span> <p>Online</p></li>
                                                                                        <li id="status-away"><span class="status-circle"></span> <p>Away</p></li>
                                                                                        <li id="status-busy"><span class="status-circle"></span> <p>Busy</p></li>
                                                                                        <li id="status-offline"><span class="status-circle"></span> <p>Offline</p></li>
                                                                                    </ul>
                                                                                </div>
                                                                                <div id="expanded">
                                                                                    <label for="twitter"><i class="fa fa-facebook fa-fw" aria-hidden="true"></i></label>
                                                                                    <input name="twitter" type="text" value="mikeross" />
                                                                                    <label for="twitter"><i class="fa fa-twitter fa-fw" aria-hidden="true"></i></label>
                                                                                    <input name="twitter" type="text" value="ross81" />
                                                                                    <label for="twitter"><i class="fa fa-instagram fa-fw" aria-hidden="true"></i></label>
                                                                                    <input name="twitter" type="text" value="mike.ross" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                    <div id="search">
                                        <label for=""><i class="fa fa-search" aria-hidden="true"></i></label>
                                        <input type="text" placeholder="Search contacts...">
                                    </div>
                                    
                                    <div id="contacts_new" class="chat_list contacts d-none">
                                        <div class="panel-heading">
                                            <i class="fa fa-times btn-close"></i>
                                        </div>
                                        <ul>
                                            <li class="contact">
                                                <div class="wrap">
                                                    <img src="http://emilcarlsson.se/assets/rachelzane.png" alt="">
                                                    <div class="meta">
                                                        <p class="name">
                                                            Shoaib 
                                                            <span class="date">
                                                                <button class="btn-accept_friend">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                                <button class="btn-refuse_friend">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                            </span>
                                                        </p>
                                                        <p class="preview">Muốn kết bạn với bạn!</p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="contact">
                                                <div class="wrap">
                                                    <img src="http://emilcarlsson.se/assets/rachelzane.png" alt="">
                                                    <div class="meta">
                                                        <p class="name">
                                                            Shoaib 
                                                            <span class="date">
                                                                <button class="btn-add_friend">
                                                                    <i class="bi bi-person-plus-fill"></i>
                                                                </button>
                                                            </span>
                                                        </p>
                                                        <p class="preview">Gửi lời mời kết bạn để bắt đầu trò chuyện</p>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div id="contacts" class="chat_list contacts">
                                        <ul>
                                            <!--
                                                                                        <li class="contact active">
                                                <div class="wrap">
                                                    <span class="contact-status busy"></span>
                                                    <img src="http://emilcarlsson.se/assets/harveyspecter.png" alt="">
                                                    <div class="meta">
                                                        <p class="name">Rishal Raza<span class="date">Apr 20</span></p>
                                                        <p class="preview">Wrong. You take the gun, or you pull out a bigger one. Or, you call their bluff. Or, you do any one of a hundred and forty six other things.</p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="contact">
                                                <div class="wrap">
                                                    <span class="contact-status away"></span>
                                                    <img src="http://emilcarlsson.se/assets/rachelzane.png" alt="">
                                                    <div class="meta">
                                                        <p class="name">Shoaib <span class="date">Apr 20</span></p>
                                                        <p class="preview">I was thinking that we could have chicken tonight, sounds good?</p>
                                                    </div>
                                                </div>
                                            </li>
                                            -->
                                            @foreach ($friends as $item)
                                                <li class="contact" data-id="{{$item->user_id}}" data-conversation="{{$item->conversation_id}}">
                                                    <div class="wrap">
                                                        <span class="contact-status online"></span>
                                                        <img src="{{$item->img}}" alt="">
                                                        <div class="meta">
                                                            <p class="name">{{$item->full_name}} <span class="date">
                                                                @php
                                                                    $updatedAt = \Carbon\Carbon::parse($item->updated_at); // Chuyển đổi thành đối tượng Carbon
                                                                @endphp
                                                                @if ($updatedAt->isToday())
                                                                    {{ $updatedAt->format('h:i A') }}, Today <!-- Hiển thị giờ và "Today" -->
                                                                @elseif ($updatedAt->isYesterday())
                                                                    {{ $updatedAt->format('h:i A') }}, Yesterday <!-- Hiển thị giờ và "Yesterday" -->
                                                                @elseif ($updatedAt->greaterThanOrEqualTo(\Carbon\Carbon::now()->subDays(3)))
                                                                    {{ $updatedAt->format('h:i A') }} <!-- Hiển thị giờ nếu trong vòng 3 ngày -->
                                                                @else
                                                                    {{ $updatedAt->format('d/m/Y') }} <!-- Hiển thị ngày tháng năm nếu lâu hơn 3 ngày -->
                                                                @endif
                                                            </span></p>
                                                            <p class="preview">{{$item->late_message}}</p>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach


                                        </ul>
                                    </div>
                                    <!--                                    <div id="bottom-bar">
                                                                            <button id="addcontact"><i class="fa fa-user-plus fa-fw" aria-hidden="true"></i> <span>Add contact</span></button>
                                                                            <button id="settings"><i class="fa fa-cog fa-fw" aria-hidden="true"></i> <span>Settings</span></button>
                                                                        </div>-->
                                </div>
                                <div class="content">
                                    <div class="contact-profile" id="profile-contact" data-id="" data-conversation="">
                                        <img class="contact-profile_avatar" src="" alt="">
                                        <p class="contact-profile_name"></p>
                                        <div class="social-media camera">
                                            <a href="#" class="video_call">
                                                <i class="fa fa-video-camera m-0" aria-hidden="true"></i>
                                            </a>
                                            <a href="#" class="common-btn">
                                                View Profile
                                            </a>
                                        </div>
                                    </div>
                                    <div class="messages">
                                        <ul>
                                            
                                        </ul>
                                    </div>
                                    <div class="message-input">
                                        <div class="wrap">
                                            <input type="text" placeholder="Write your message...">
                                            <i class="fa fa-paperclip attachment" aria-hidden="true"></i>
                                            <button class="sendMsg"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @vite('resources/js/app.js')
<script>
setTimeout(() => {
    window.Echo.channel('chat').listen('chat',(data)=>{
         // Add message
        let conversation_id = $('#profile-contact').attr('data-conversation');
        let receiver_id = $('#profile').attr('data-id'); 
        let receiver_img = $('#profile-contact .contact-profile_avatar').attr('src');
        console.log('Nhận socket conversation_id:'+data['conversation_id']);
        console.log('Trên ứng dụng conversation_id:'+conversation_id);
        console.log('Người nhận receiver_id:'+receiver_id);
        console.log('Người nhận socket receiver_id:'+data['receiver_id']);
        
        if(conversation_id == data['conversation_id'] && receiver_id == data['receiver_id'])
        {
            $('.messages ul').append(`
            <li class="replies">
                <img src="${receiver_img}" alt="">
                <p>${data.message}</p>
                <span class="msg_time">8:40 AM, Today</span>
            </li>
            `);  
        }
        else
        {
            if(receiver_id == data['receiver_id'])
            {
                let test = $(`#contacts .contact[data-conversation="${data['conversation_id']}"]`);
                test.addClass('coler-black');
                test.find('.preview').text(`${data.message}`);
            }
        } 

    })
}, 500);

document.getElementById('profile-img').addEventListener('click', function() {
        window.location.href = '{{ route('user') }}';
});
</script>
<script src="js/index.js"></script>
</body>
</html>