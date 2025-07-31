@extends('layout.app')
@section('content')
        <div class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
            <!--begin::Content body-->
            <div class="d-flex flex-column-fluid flex-center">
                <h2>Xác minh email của bạn</h2>
                <p>Chúng tôi đã gửi một liên kết xác minh đến email của bạn.</p>

                @if (session('message'))
                    <div class="alert alert-success">{{ session('message') }}</div>
                @endif

                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary mt-3">Gửi lại email xác minh</button>
                </form>
            </div>
            <!--end::Content body-->
            <!--begin::Content footer-->
            @include('layout.footer')
            <!--end::Content footer-->
        </div>
@endsection

