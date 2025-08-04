@extends('layout.app')
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
                    <h2>Xác minh email của bạn</h2>
                    <p>Chúng tôi đã gửi một liên kết xác minh đến email của bạn.</p>

                    @if (session('message'))
                        <div class="alert alert-success">{{ session('message') }}</div>
                    @endif

                    <form method="POST" id="resend-form" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary mt-3" id="resend-btn"
                        {{ session('resent') ? 'disabled' : '' }}
                        >Gửi lại email xác minh</button>
                    </form>
                    <p id="countdown-text" class="mt-2 text-danger" style="display: none;">
                        Vui lòng chờ <span id="countdown">60</span>s để gửi lại.
                    </p>
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
<script>
    document.addEventListener("DOMContentLoaded", function(){
        const resendBtn = document.getElementById('resend-btn');
        const countdownText = document.getElementById('countdown-text');
        const countdownSpan = document.getElementById('countdown');

        // Hàm đếm ngược
        function startCountdown(remainingSeconds){
            resendBtn.disabled = true;
            countdownText.style.display = 'block';
            const countdown = setInterval(() =>{
                remainingSeconds--;
                countdownSpan.textContent = remainingSeconds;

                if(remainingSeconds <=0){
                    clearInterval(countdown);
                    resendBtn.disabled = false;
                    countdownText.style.display = 'none';
                    countdownSpan.textContent = 60;
                    localStorage.removeItem('resend-start-time');
                }
            },1000);
        }
        // Khi submit form ->lưu time hiện tại
        document.getElementById('resend-form').addEventListener('submit',function(){
            const now = Date.now();
            localStorage.setItem('resend-start-time',now);
        })

        // Khi trang load -> kiểm tra nếu có thời gian đếm
        const startTime = localStorage.getItem('resend-start-time');
        if(startTime){
            const now = Date.now();
            const elapsed = Math.floor((now - startTime)/1000);
            const remaining = 60-elapsed;
            if(remaining > 0){
                startCountdown(remaining);
            }else{
                localStorage.removeItem('resend-start-time');
            }
        }
    });
</script>
@endsection

