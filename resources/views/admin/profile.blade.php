
@extends('admin.layout.app')
@section('title',' Chỉnh sửa thông tin cá nhân')
@section('page-title','Thông tin tài khoản')
@section('content')
    <!--begin::Content-->
    <div class="flex-row-fluid ml-lg-8">
        <!--begin::Card-->
        <div class="card card-custom card-stretch">
            <!--begin::Header-->
            <div class="card-header py-3">
                <div class="card-title align-items-start flex-column">
                    <h3 class="card-label font-weight-bolder text-dark">Thông tin cá nhân</h3>
                    <span class="text-muted font-weight-bold font-size-sm mt-1">Cập nhật thông tin cá nhân</span>
                </div>

            </div>
            <!--end::Header-->
            <!--begin::Form-->
            <form class="form" action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
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
                        @if ($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" width="100" class="mb-2"><br>
                        @endif
                        <input type="file" name="avatar">
                        </div>
                        @error('avatar')
                            <div class="text-danger"> {{$message}} </div>
                        @enderror
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Họ tên</label>
                        <div class="col-lg-9 col-xl-6">
                            <input class="form-control form-control-lg form-control-solid" name="name" type="text" value="{{ old('name', $user->name) }}" />
                        </div>
                        @error('name')
                            <p class="text-danger"> {{$message}} </p>
                        @enderror
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
                                <input type="text" class="form-control form-control-lg form-control-solid" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Phone" />

                            </div>
                            @error('phone')
                                <p class="text-danger"> {{$message}} </p>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Địa chỉ Email (Không thể sửa)</label>
                        <div class="col-lg-9 col-xl-6">
                            <div class="input-group input-group-lg input-group-solid">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="la la-at"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control form-control-lg form-control-solid" name="email" value="{{ old('email', $user->email) }}" disabled />
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Ngày sinh</label>
                        <div class="col-lg-9 col-xl-6">
                            <div class="input-group input-group-lg input-group-solid">
                                <input type="date" class="form-control form-control-lg form-control-solid" name="dob" placeholder="Ngày sinh" value="{{ old('dob', $user->dob) }}" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Địa chỉ</label>
                        <div class="col-lg-9 col-xl-6">
                            <input class="form-control form-control-lg form-control-solid" name="address" type="text" value="{{ old('address', $user->address) }}" />
                        </div>
                    </div>
                    <div class="card-toolbar">
                    <button type="submit" class="btn btn-success mr-2">Lưu thay đổi</button>
                    <a href="{{route('admin.dashboard')}}" class="btn btn-secondary">Huỷ</a>
                    </div>
                </div>
                <!--end::Body-->
            </form>
            <!--end::Form-->
        </div>
    </div>
    <!--end::Content-->
@endsection
