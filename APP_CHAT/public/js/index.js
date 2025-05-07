// Mặc định

    // sender
$('#profile') 
    // receiver
$('#profile-contact')
console.log($('#profile-contact'));

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

            if (response.success) {
                response.messages.forEach(function(msg) {
                    if (msg.sender_id == profile_id) {
                        // người gửi tin nhắn bên trái
                        $('.messages ul').append(`
                            <li class="sent">
                                <img src="${profile_img}" alt="">
                                <p>${msg.message}</p>
                                <span class="msg_time">8:40 AM, Today</span>
                            </li>
                        `);
                    } else {
                        // Người nhận  → tin nhắn bên phải
                        $('.messages ul').append(`
                            <li class="replies">
                                <img src="${profile_contact_avatar}" alt="">
                                <p>${msg.message}</p>
                                <span class="msg_time">8:40 AM, Today</span>
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
    $('.contact').first().addClass('active');
    // Get info
    let profile_contact_id = $('.contact').first().attr('data-id');
    let profile_contact_avatar = $('.contact').first().find('img').attr('src');
    let profile_contact_fullName = $('.contact').first().find('.meta .name').contents().first().text().trim();

    let conversation_id = $('.contact').first().attr('data-conversation');

    let profile_id = $('#profile').attr('data-id');
    let profile_img = $('#profile').find('img').attr('src');

    // repleace contact-profile
    $('#profile-contact').attr('data-id',profile_contact_id);
    $('#profile-contact').attr('data-conversation',profile_contact_id);
    $('#profile-contact .contact-profile_name').text(profile_contact_fullName);
    $('#profile-contact .contact-profile_avatar').attr('src', profile_contact_avatar);

    showMess(conversation_id,profile_id,profile_img,profile_contact_avatar);
    
});

// select conversation
$(document).on('click', '#contacts .contact',function(){

    // Get info
    let profile_contact_id = $(this).attr('data-id');
    let profile_contact_avatar = $(this).find('img').attr('src');
    let profile_contact_fullName = $(this).find('.meta .name').clone().children().remove().end().text().trim();

    let conversation_id = $(this).attr('data-conversation');

    let profile_id = $('#profile').attr('data-id');
    let profile_img = $('#profile').find('img').attr('src');

    // repleace contact-profile
    $('#profile-contact').attr('data-id',profile_contact_id);
    $('#profile-contact').attr('data-conversation',profile_contact_id);
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

    let sender_id = $('#profile').attr('data-id'); 
    let sender_img = $('#profile').find('img').attr('src');
    
    console.log("ngươi nhận" + receiver_id + receiver_img );
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
        },
        dataType: "json", // Đảm bảo phản hồi JSON
        success: function (response) {
            console.log(response);
            $('.messages ul').append(`
                <li class="sent">
                    <img src="${sender_img}" alt="">
                    <p>${message}</p>
                    <span class="msg_time">9:00 AM, Today</span>
                </li>
            `);
        }
    });
    // Add message
    
    // Set Input value = ""
    $('.message-input input').val("");
});


