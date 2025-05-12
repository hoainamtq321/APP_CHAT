<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Infomation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous"/>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <link rel="stylesheet" href="/css/infomation.css">
</head>
<body>
    <div class="container">
       <div class="Back">
            <i class="fa fa-arrow-left" onclick=back() style="cursor: pointer"></i>
        </div>
        <p class="h2 text-center">Thông tin người dùng</p>
        <form action="{{route('user.updateInfo')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="preview text-center">
                <img class="preview-img" src="{{Auth::user()->img}}" alt="Preview Image" width="200" height="200"/>
                <div class="browse-button">
                    <i class="fa fa-pencil-alt"></i>
                    <input class="browse-input" type="file" name="img" id="UploadedFile" accept="image/*" />
                </div>
                <span class="Error"></span>
            </div>
            <div class="form-group">
                <label>Full Name:</label>
                <input class="form-control" type="text" name="full_name" required placeholder="Enter Your Full Name" value="{{Auth::user()->full_name}}"/>
                <span class="Error"></span>
            </div>
        
            <div class="form-group">
                <input class="btn btn-primary btn-block" type="submit" value="Cập nhật thông tin"/>
            </div>
        </form>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <div class="form-group">
                <input class="btn btn-primary btn-block" type="submit" value="Đăng xuất"/>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
document.getElementById('UploadedFile').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.querySelector('.preview-img').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

function back()
{
    window.location.href = '{{ route('chat') }}';
}
</script>
</body>
</html>