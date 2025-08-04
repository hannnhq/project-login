@extends('layout.app')
@section('title','Đăng ký cho admin')
@section('content')
    <div class="login login-1 login-signup-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
        <!--begin::Aside-->
        @include('layout.aside')
        <!--begin::Aside-->
        <!--begin::Content-->
        <div class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
            <!--begin::Content body-->
            <div class="d-flex flex-column-fluid flex-center">
                <!--begin::Signup-->
                <div class="login-form login-signup">
                    <!--begin::Form-->
                    <form class="form" action="{{route('signup.store.admin')}}" method="POST" novalidate="novalidate" id="">
                        <!--begin::Title-->
                        @csrf
                        <div class="pb-13 pt-lg-0 pt-5">
                            <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Đăng ký quản trị viên</h3>
                            <p class="text-muted font-weight-bold font-size-h4">Nhập thông tin của bạn để tạo tài khoản</p>
                        </div>
                        <!--end::Title-->
                        <!--begin::Form group-->
                        <div class="form-group">
                            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6" value="{{old('name')}}" type="text" placeholder="Họ tên" name="name" autocomplete="off"/>
                            @error('name')
                                <p class="text-danger mt-2">{{$message}}</p>
                            @enderror
                        </div>
                        <!--end::Form group-->
                        <!--begin::Form group-->
                        <div class="form-group">
                            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6" value="{{old('email')}}" type="email" placeholder="Email" name="email" autocomplete="off"/>
                            @error('email')
                                <p class="text-danger mt-2"> {{$message}} </p>
                            @enderror
                        </div>
                        <!--end::Form group-->
                        <!--begin::Form group-->
                        <div class="form-group">
                            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6" type="password" placeholder="Mật khẩu" name="password" autocomplete="off"/>
                            @error('password')
                                <p class="text-danger mt-2">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6" type="password" placeholder="Nhập lại mật khẩu" name="cpassword" autocomplete="off"/>
                            @error('cpassword')
                                <p class="text-danger mt-2">{{$message}}</p>
                            @enderror
                        </div>
                        <!--end::Form group-->
                        <!--begin::Form group-->
                        <div class="form-group">
                            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6" value="{{old('address')}}" type="address" placeholder="Địa chỉ" name="address" autocomplete="off" >
                        </div>
                        <!--end::Form group-->
                        <!--begin::Form group-->
                        <div class="form-group d-flex flex-wrap pb-lg-0 pb-3">
                            <button type="submit" id="kt_login_signup_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-4">Đăng ký</button>
                            <a href="{{route('login')}}" id="kt_login_signup_cancel" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3">Huỷ</a>
                        </div>
                        <!--end::Form group-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Signup-->
            </div>
            <!--end::Content body-->
            <!--begin::Content footer-->
            @include('layout.footer')
            <!--end::Content footer-->
        </div>
        <!--end::Content-->
    </div>
@endsection
