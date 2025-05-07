<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Welcome to App Chat</h1>
    <button onclick="clickMe()">Click me</button>

    @vite('resources/js/app.js')
    <script>
    function clickMe(){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            url:'{{route('broadcast.chat')}}',
            method: "GET",
            success : function(data){
                console.log(data);
            }
        });
    }    

    setTimeout(() => {
        window.Echo.channel('chatMessage').listen('chat',(data)=>{
            console.log(data);
            
        })
    }, 500);
    </script>
    <script src="js/jquery-3.7.1.js"></script>
</body>
</html>