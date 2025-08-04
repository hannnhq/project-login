@extends('layout.app')
@section('title','Đăng nhập')

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
                    @if (session('message'))
                        <p class="alert alert-success"> {{session('message')}} </p>
                    @endif
                    @if ($errors->has('message'))
                        <div class="alert alert-danger" id="many-attempts">
                            {{ $errors->first('message') }}
                        </div>
                    @endif
                    @if (session('lock_time'))
                        <div class="alert alert-danger" id="lock-message">
                            {{ session('lock_time') }}
                        </div>
                    @endif

                    <form class="form" action="{{route('login.submit')}}" method="POST" novalidate="novalidate" id="">
                        <!--begin::Title-->
                        @csrf
                        <div class="pb-13 pt-lg-0 pt-5">
                            <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Chào mừng bạn đến với HQ Group</h3>
                            <span class="text-muted font-weight-bold font-size-h4">Chúc bạn một ngày tốt lạnh!</span>
                        </div>

                        <div class="pb-lg-0 pb-5">
                        <a href="{{route('login')}}" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">Sign In</a>
                        <a href="{{route('signup.user')}}" class="btn btn-warning font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">Sign Up</a>
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
