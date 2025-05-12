// Mặc định
dayjs.extend(dayjs_plugin_isToday);
dayjs.extend(dayjs_plugin_isYesterday);
// btn-close
$('.btn-close').on('click',function(){
    $('#contacts_new').find('li').remove();
    $('#contacts_new').addClass('d-none');
});
/*Begin Add friend*/
$(document).on('click',".pending-requests i", function(){
    $('#contacts_new').removeClass('d-none')
    $('#contacts_new').find('li').remove();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: "friendRequest",
        data: { 
        },
        dataType: "json", // Đảm bảo phản hồi JSON
        success: function (response) {
            let searchResult = $("#contacts_new ul");// Nơi hiển thị kết quả
            console.log(response.data.length);
            if(response.data.length == 0)
            {
                $(".pending-requests .icon-badge").addClass('d-none');
            }
            else
            {
                $(".pending-requests .icon-badge").text(response.data.length).removeClass('d-none');
            }
            response.data.forEach(item  =>{
                searchResult.append(
                    `
                        <li class="contact" data-id=${item.user_id}>
                            <div class="wrap">
                                <img src="${item.img}" alt="">
                                <div class="meta">
                                    <p class="name">
                                        ${item.full_name}
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
                    `
                )
            })
        }
    });
});

$(document).on('click',".btn-accept_friend",function(){

    let user_id = $(this).closest('.contact').data('id');
    $(this).closest('.contact').remove();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: "acceptRequest",
        data: { 
            user_id: user_id
        },
        dataType: "json", // Đảm bảo phản hồi JSON
        success: function (response) {
            console.log(response);
        }
    });
});

$(document).on('click',".btn-refuse_friend",function(){
    let user_id = $(this).closest('.contact').data('id');
    $(this).closest('.contact').remove();
    
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: "refuseRequest",
        data: { 
            user_id: user_id
        },
        dataType: "json", // Đảm bảo phản hồi JSON
        success: function (response) {
            console.log(response);
        }
    });
});
/*End Add friend*/

/*Begin Search friend*/
function searchFriend(full_name){
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: "searchFriend",
        data: { 
            full_name: full_name
        },
        dataType: "json", // Đảm bảo phản hồi JSON
        success: function (response) {
            let searchResult = $("#contacts_new ul");// Nơi hiển thị kết quả
            
            response.data.forEach(item  =>{
                searchResult.append(`
                    <li class="contact" data-id=${item.user_id}>
                        <div class="wrap">
                            <img src="${item.img}" alt="">
                            <div class="meta">
                                <p class="name">
                                    ${item.full_name} 
                                    <span class="date">
                                        <!-- Kiểm tra nếu friend = 0 thì hiển thị nút, nếu friend = 1 thì ẩn -->
                                        ${item.friend === 0 ? `
                                            <button class="btn-add_friend">
                                                <i class="bi bi-person-plus-fill"></i>
                                            </button>
                                        ` : ''}
                                    </span>
                                </p>
                                <p class="preview">
                                    ${item.friend === 1 ? 
                                        `Đã là bạn của nhau` : `Gửi lời mời kết bạn để bắt đầu trò chuyện`
                                    }
                                
                                </p>
                            </div>
                        </div>
                    </li>                         
                `);
            })
        }
    });
}
$(document).on('click','#search input', function(){
    $('#contacts_new').removeClass('d-none');
    $('#contacts_new').find('li').remove();
    let full_name = "start";
    searchFriend(full_name);
});

let timer;
$(document).on('input', ("#search input"),function(){
    clearTimeout(timer); // Xoá timer cũ nếu người dùng vẫn đang gõ
    let full_name = $(this).val();
    
    timer = setTimeout(() => {
        $('#contacts_new').find('li').remove();
        searchFriend(full_name);
        // Gọi hàm tìm kiếm ở đây
    }, 500); // Chờ 500ms sau khi người dùng ngừng gõ
})

$(document).on('click', '.btn-add_friend', function() {
    $(this).addClass('d-none');
    let user_id = $(this).closest('.contact').data('id');

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: "sendRequest",
        data: { 
            user_id: user_id
        },
        dataType: "json", // Đảm bảo phản hồi JSON
        success: function (response) {
            console.log(response);
        }
    });

});
/*End Search friend*/
    // sender
$('#profile') 
    // receiver
$('#profile-contact')

// Show message
function showMess(conversation_id,profile_id,profile_img,profile_contact_avatar){
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: "showmessage",
        data: { 
            conversation_id:conversation_id
        },
        dataType: "json", // Đảm bảo phản hồi JSON
        success: function (response) {
            console.log(response.messages.data);
            console.log(profile_id);
            if (response.success) {
                response.messages.data.forEach(function(msg) {
                    const createdAt = dayjs(msg.created_at);
                    let formattedTime = "";

                    if (createdAt.isToday()) {
                        formattedTime = createdAt.format("h:mm A") + ", Today";
                    } else if (createdAt.isYesterday()) {
                        formattedTime = createdAt.format("h:mm A") + ", Yesterday";
                    } else if (createdAt.isAfter(dayjs().subtract(3, 'day'))) {
                        formattedTime = createdAt.format("h:mm A");
                    } else {
                        formattedTime = createdAt.format("DD/MM/YYYY");
                    }
                    console.log(msg.sender_id)
                    if (msg.sender_id == profile_id) {
                        // người gửi tin nhắn bên trái
                        $('.messages ul').append(`
                            <li class="sent">
                                <img src="${profile_img}" alt="">
                                <p>${msg.content}</p>
                                <span class="msg_time">${formattedTime}</span>
                            </li>
                        `);
                    } else {
                        // Người nhận  → tin nhắn bên phải
                        $('.messages ul').append(`
                            <li class="replies">
                                <img src="${profile_contact_avatar}" alt="">
                                <p>${msg.content}</p>
                                <span class="msg_time">${formattedTime}</span>
                            </li>
                            `);  
                    }
                });
            }
        }
    });
}

// Show recent messages
$(document).ready(function () {
    $('#contacts .contact').first().addClass('active');
    // Get info
    let profile_contact_id = $('#contacts .contact').first().attr('data-id');
    let profile_contact_avatar = $('#contacts .contact').first().find('img').attr('src');
    let profile_contact_fullName = $('#contacts .contact').first().find('.meta .name').contents().first().text().trim();

    let conversation_id = $('#contacts .contact').first().attr('data-conversation');

    let profile_id = $('#profile').attr('data-id');
    let profile_img = $('#profile').find('img').attr('src');

    // repleace contact-profile
    $('#profile-contact').attr('data-id',profile_contact_id);
    $('#profile-contact').attr('data-conversation',conversation_id);
    $('#profile-contact .contact-profile_name').text(profile_contact_fullName);
    $('#profile-contact .contact-profile_avatar').attr('src', profile_contact_avatar);

    showMess(conversation_id,profile_id,profile_img,profile_contact_avatar);
    
});

// select conversation
$(document).on('click', '#contacts .contact',function(){
    $(this).addClass('coler-f5');
    // Get info
    let profile_contact_id = $(this).attr('data-id');
    let profile_contact_avatar = $(this).find('img').attr('src');
    let profile_contact_fullName = $(this).find('.meta .name').clone().children().remove().end().text().trim();

    let conversation_id = $(this).attr('data-conversation');

    let profile_id = $('#profile').attr('data-id');
    let profile_img = $('#profile').find('img').attr('src');

    // repleace contact-profile
    $('#profile-contact').attr('data-id',profile_contact_id);
    $('#profile-contact').attr('data-conversation',conversation_id);
    $('#profile-contact .contact-profile_name').text(profile_contact_fullName);
    $('#profile-contact .contact-profile_avatar').attr('src', profile_contact_avatar);
    
    // repleace message
        // Delete old messages
    $('.messages ul').empty();
        // showMessage 
    showMess(conversation_id,profile_id,profile_img,profile_contact_avatar);

    $('#contacts .active').removeClass('active');
    $(this).addClass('active');
 
});


// Send Message
$(document).on('click', '.sendMsg', function() {

    // Input mess
    let message = $('.message-input input').val();
    let receiver_id = $('#profile-contact').attr('data-id'); 
    let receiver_img = $('#profile-contact .contact-profile_avatar').attr('src');

    let conversation_id = $('#profile-contact').attr('data-conversation'); 

    let sender_id = $('#profile').attr('data-id'); 
    let sender_img = $('#profile').find('img').attr('src');
    
    console.log("ngươi nhận" + receiver_id + receiver_img );
    console.log("Mối quan hệ" + conversation_id );
    console.log("Nguoi gửi" + sender_id + sender_img);

    
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: "broadcast",
        data: { 
            message: message,
            receiver_id: receiver_id,
            sender_id: sender_id,
            conversation_id: conversation_id
        },
        dataType: "json", // Đảm bảo phản hồi JSON
        success: function (response) {
            const now = dayjs(); // Thời gian hiện tại
            let formattedTime = "";

            if (now.isToday()) {
                formattedTime = now.format("h:mm A") + ", Today";
            }
            console.log(response);
            $('.messages ul').append(`
                <li class="sent">
                    <img src="${sender_img}" alt="">
                    <p>${message}</p>
                    <span class="msg_time">${formattedTime}</span>
                </li>
            `);
        }
    });
    
    // Add message
    
    // Set Input value = ""
    $('.message-input input').val("");
});




