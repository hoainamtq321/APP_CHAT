@extends('auth.master')
@section('form')
    <form action="" method="post" class="formLogin mx-auto">
        @csrf
        @if (session('msg'))
            <div class="err-warning">
                <p>{{session('msg')}}</p>
            </div>
        @endif
        <h1>Đăng nhập</h1>
        <input class="col-1" type="text" id="username" name="username" placeholder="Tên đăng nhập">
        <input class="col-1" type="password" id="password" name="password" placeholder="Mật khẩu">
        <div class="formLogin-btn">
            <button type="submit">Đăng nhập</button>
            <a href="{{route('fromRegister')}}">
                <button type="button">Đăng ký</button>
            </a>
        </div>
    </form>

@endsection