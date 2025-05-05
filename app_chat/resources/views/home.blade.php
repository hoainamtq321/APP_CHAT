<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>App chat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
</head>
<body>
    
    <div class="container">
        
        <h1>Hello</h1>

        <button type="button" onclick="clickMy()">Clickme</button>
    </div>


        <label for="message">Tin nhắn</label>
        <input type="text" id="message" name="message">
        <button type="button" onclick="clickMy2()">Gửi</button>

        <div id="showMess">

        </div>

    @vite('resources/js/app.js')
    <script>
    function clickMy(){
        console.log('abc');
        $.ajax({
            headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}'},
            type:'POST',
            url: "{{ route('broadcast.chat') }}",
            success: function(data){
                console.log(data);
            }
        })
    }

    function clickMy2(){
        let message = $("#message").val();
        $.ajax({
            headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}'},
            type:'POST',
            data:{
                message:message
            },
            url: "{{ route('broadcast.sendMail') }}",
            success: function(response){
                console.log(response,"abc");
                $("#showMess").append(
                    `
                    <p>${response.message}</p>
                    `
                );
            }
        })
    }

    setTimeout(() => {
        Echo.channel('channel_Message').listen('chat',(data)=>{
            console.log(data);
            $("#showMess").append(
                    `
                    <p>${data.message}</p>
                    `
                );
        })
    }, 100);

    </script>
</body>
</html>