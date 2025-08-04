@extends('layout.app')
@section('title','Thiết lập 2FA')

@section('content')
    <div class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
        <!--begin::Aside-->
        @include('layout.aside')
        <!--begin::Aside-->

        <!--begin::Content-->
        <div class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
            <!--begin::Content body-->
            <div class="d-flex flex-column-fluid flex-center">
                <!--begin::Setup 2FA-->
                <div class="login-form login-signin text-center">
                    <h3 class="mb-10 font-weight-bold text-dark">Thiết lập xác thực 2 bước (2FA)</h3>

                    <p class="text-muted mb-5">Quét mã QR bên dưới bằng ứng dụng Google Authenticator:</p>

                    <div class="d-flex justify-content-center mb-5">
                        <img src="{{ $qrCodeUrl }}">
                    </div>
                    <p>Nếu bạn không quét được mã vui lòng nhập mã secret dưới đây</p>
                    <p><strong>Tên mã: {{$email}}</strong></p>
                    <p><strong>Mã bí mật:</strong> {{ $secret }}</p>

                    <p class="text-muted">Sau khi thiết lập, hãy nhập mã OTP để xác thực.</p>

                    <a href="{{ route('2fa.form') }}" class="btn btn-primary mt-4 px-8 py-3 font-weight-bold">Nhập mã OTP</a>
                </div>
                <!--end::Setup 2FA-->
            </div>
            <!--end::Content body-->

            <!--begin::Content footer-->
            @include('layout.footer')
            <!--end::Content footer-->
        </div>
        <!--end::Content-->
    </div>
@endsection
