@extends('layout.app')
@section('title','Đặt lại mật khẩu')

@section('content')
    <div class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
        <!--begin::Aside-->
        @include('layout.aside')
        <!--begin::Aside-->
        <!--begin::Content-->
        <div class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
            <!--begin::Content body-->
            <div class="d-flex flex-column-fluid flex-center">
                <!--begin::Signin-->
                <div class="login-form login-signin">
                    <!--begin::Form-->
                    @if (session('success'))
                        <p class="alert alert-success"> {{session('success')}} </p>
                    @endif

                    <form class="form" action="{{route('password.update')}}" method="POST" novalidate="novalidate" id="">
                        <!--begin::Title-->
                        @csrf
                        <div class="pb-13 pt-lg-0 pt-5">
                            <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Đổi mật khẩu</h3>
                        </div>
                        <!--begin::Title-->
                        <input type="hidden" name="token" value="{{$token}}">
                        <input type="hidden" name="email" value="{{$email}}">
                        <div class="form-group">
                            <label class="font-size-h6 font-weight-bolder text-dark">Mật khẩu mới</label>
                            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg" type="password" name="password" autocomplete="off" value="{{old('new_password')}}"/>
                            @error('password')
                                <p class="text-danger mt-3"> {{$message}} </p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="font-size-h6 font-weight-bolder text-dark">Xác nhận mật khẩu mới</label>
                            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg" type="password" name="password_confirmation" autocomplete="off"/>

                        </div>
                        <!--begin::Action-->
                        <div class="pb-lg-0 pb-5">
                            <button type="submit" id="kt_login_signin_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">Xác nhận</button>
                        </div>
                        <!--end::Action-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Signin-->

            </div>
            <!--end::Content body-->
            <!--begin::Content footer-->
            @include('layout.footer')

            <!--end::Content footer-->
        </div>
        <!--end::Content-->
    </div>
@endsection
