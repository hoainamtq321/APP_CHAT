<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login App</title>

    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->
    
    
    <!--Pulling Awesome Font -->
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

    <!--Css-->
    <link rel="stylesheet" href="css/styles.css">

</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-offset-5 col-md-3">
                <form class="form-login" method="POST" action="{{route('login')}}">
                    @csrf
                    <h4>Welcome back.</h4>
                    @if (session('msg'))
                        <div class="alert alert-danger" role="alert">
                            <p>{{session('msg')}}</p>
                        </div>
                    @endif
                    <input type="text" id="username" name="username" class="form-control input-sm chat-input" placeholder="username" />
                    </br>
                    <input type="password" id="password" name="password" class="form-control input-sm chat-input" placeholder="password" />
                    </br>
                    <div class="wrapper">
                        <span class="group-btn">     
                            <button type="submit"  class="btn btn-primary btn-md">
                                login 
                                <i class="fa fa-sign-in"></i>
                            </button>
                        </span>
                    </div>
                </form>
            
            </div>
        </div>
    </div>

    
</body>
</html>