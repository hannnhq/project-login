@extends('admin.layout.app')
@section('title','Thông tin cá nhân')
@section('page-title','Thông tin tài khoản')

@section('content')
    <!--begin::Content-->

    <div class="flex-row-fluid ml-lg-8">
            @if (session('success'))
                    <div class="alert alert-custom alert-light-success fade show mb-10" role="alert">
                        <div class="alert-text font-weight-bold">{{session('success')}}</div>
                        <div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">
                                    <i class="ki ki-close"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                    @endif
        <!--begin::Card-->
        <div class="card card-custom card-stretch">
            <!--begin::Header-->
            <div class="card-header py-3">
                <div class="card-title align-items-start flex-column">
                    <h3 class="card-label font-weight-bolder text-dark">Thông tin người dùng</h3>
                </div>
            </div>
            <!--end::Header-->
            <!--begin::Form-->
            <form class="form">
                <!--begin::Body-->
                <div class="card-body">

                    <div class="row">
                        <label class="col-xl-3"></label>
                        <div class="col-lg-9 col-xl-6">
                            <h5 class="font-weight-bold mb-6">Thông tin</h5>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Avatar</label>
                        <div class="col-lg-9 col-xl-6">
                            <div class="image-input image-input-outline" id="kt_profile_avatar" style="background-image: url(assets/media/users/blank.png)">
                                <div class="image-input-wrapper" style="background-image: url('{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('default-avatar.png') }}')"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Họ tên</label>
                        <div class="col-lg-9 col-xl-6">
                            <input class="form-control form-control-lg form-control-solid" name="name" type="text" value="{{ $user->name }}" disabled/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Vai trò</label>
                        <div class="col-lg-9 col-xl-6">
                            <input class="form-control form-control-lg form-control-solid" name="role" type="text" value="{{ $user->role }}" disabled/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Số điện thoại</label>
                        <div class="col-lg-9 col-xl-6">
                            <div class="input-group input-group-lg input-group-solid">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="la la-phone"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control form-control-lg form-control-solid" name="phone" value="{{ $user->phone ?? 'Chưa thiết lập' }}" placeholder="Phone" disabled/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Email</label>
                        <div class="col-lg-9 col-xl-6">
                            <div class="input-group input-group-lg input-group-solid">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="la la-at"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control form-control-lg form-control-solid" name="email" value="{{ $user->email }}" disabled />
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Ngày sinh</label>
                        <div class="col-lg-9 col-xl-6">
                            <div class="input-group input-group-lg input-group-solid">
                                <input type="date" class="form-control form-control-lg form-control-solid" name="dob" placeholder="Ngày sinh" value="{{ $user->dob ?? 'Chưa thiết lập' }}" disabled/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Địa chỉ</label>
                        <div class="col-lg-9 col-xl-6">
                            <input class="form-control form-control-lg form-control-solid" name="address" type="text" value="{{ $user->address ?? 'Chưa thiết lập' }}" disabled/>
                        </div>
                    </div>
                                        <div class="card-toolbar">

                    <a href="{{route('admin.list-account')}}" class="btn btn-secondary">Quay lại</a>
                    </div>
                </div>


                <!--end::Body-->
            </form>
            <!--end::Form-->
        </div>
    </div>
    <!--end::Content-->
@endsection
