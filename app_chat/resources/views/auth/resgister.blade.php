@extends('auth.master')
@section('form')
    <form action="{{route('register')}}" method="post" class="formLogin mx-auto">
        @csrf
        @if (session('msg'))
            <div class="err-warning">
                <p>{{session('msg')}}</p>
            </div>
        @endif
        <h1>Đăng ký</h1>
        <input class="col-1" type="text" id="username" name="username" placeholder="Tên đăng nhập">
        <input class="col-1" type="password" id="password" name="password" placeholder="Mật khẩu">
        <input class="col-1" type="password" id="password_confirmation " name="password_confirmation" placeholder="Nhập lại mật khẩu">
        <div class="formLogin-btn">
            <a href="{{route('fromlogin')}}">
                <button type="button">Quay lại</button>
            </a>
            <button type="submit">Đăng ký</button>
        </div>
    </form>

@endsection