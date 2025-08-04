@extends('admin.layout.app')
@section('title','Đổi mật khẩu')
@section('page-title', 'Đổi mật khẩu')
@section('content')
    <div class="flex-row-fluid ml-lg-8">
        <!--begin::Card-->
        <div class="card card-custom">
            <!--begin::Header-->
            <div class="card-header py-3">
                <div class="card-title align-items-start flex-column">
                    <h3 class="card-label font-weight-bolder text-dark">Thay đổi mật khẩu</h3>
                    <span class="text-muted font-weight-bold font-size-sm mt-1">Thay đổi mật khẩu tài khoản</span>
                </div>
            </div>
            <!--end::Header-->
            <!--begin::Form-->
            <form class="form" action="{{route('admin.changepassword')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <!--begin::Alert-->
                    {{-- <div class="alert alert-custom alert-light-danger fade show mb-10" role="alert">
                        <div class="alert-icon">
                            <span class="svg-icon svg-icon-3x svg-icon-danger">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Info-circle.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10" />
                                        <rect fill="#000000" x="11" y="10" width="2" height="7" rx="1" />
                                        <rect fill="#000000" x="11" y="7" width="2" height="2" rx="1" />
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                        </div>
                        <div class="alert-text font-weight-bold"></div>
                        <div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">
                                    <i class="ki ki-close"></i>
                                </span>
                            </button>
                        </div>
                    </div> --}}
                    <!--end::Alert-->
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label text-alert">Mật khẩu cũ</label>
                        <div class="col-lg-9 col-xl-6">
                            <input type="password" class="form-control form-control-lg form-control-solid mb-2" name="current_password" value="{{old('current_password')}}" placeholder="Nhập mật khẩu cũ" />
                            <a href="{{route('forgotpassword')}}" class="text-sm font-weight-bold">Quên mật khẩu ?</a>
                            @error('current_password')
                            <p class="text-danger mt-3"> {{$message}} </p>
                        @enderror
                        </div>

                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label text-alert">Mật khẩu mới</label>
                        <div class="col-lg-9 col-xl-6">
                            <input type="password" class="form-control form-control-lg form-control-solid" name="new_password" value="{{old('new_password')}}"  placeholder="Nhập mật khẩu mới" />
                            @error('new_password')
                                <p class="text-danger mt-3"> {{$message}} </p>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label text-alert">Xác nhận mật khẩu mới</label>
                        <div class="col-lg-9 col-xl-6">
                            <input type="password" class="form-control form-control-lg form-control-solid" name="new_password_confirm"  placeholder="Nhập lại mật khẩu mới" />
                            @error('new_password_confirm')
                                <p class="text-danger mt-3"> {{$message}} </p>
                            @enderror
                        </div>
                    </div>
                    <div class="card-toolbar">
                    <button type="submit" class="btn btn-success mr-2">Xác nhận</button>
                    <a href="{{route('admin.dashboard')}}" class="btn btn-secondary">Huỷ</a>
                </div>
                </div>
            </form>
            <!--end::Form-->
        </div>
    </div>
@endsection
