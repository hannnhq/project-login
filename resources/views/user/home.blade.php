@extends('layout.app')
@section('content')
<div class="container">
    <!--begin::Header-->
    <div id="" class="header">
        <!--begin::Container-->
        <div class="container-fluid d-flex align-items-stretch justify-content-between">
            <div class="topbar">
                <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn đăng xuất?')">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-light-danger mt-5">Đăng xuất</button>
                </form>
                <!--begin::Search-->
                <div class="dropdown" id="kt_quick_search_toggle">
                    <!--begin::Toggle-->
                    <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
                        <div class="btn btn-icon btn-clean btn-lg btn-dropdown mr-1">
                            <span class="svg-icon svg-icon-xl svg-icon-primary">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/General/Search.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        <path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero" />
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                        </div>
                    </div>
                    <!--end::Toggle-->
                    <!--begin::Dropdown-->
                    <div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg">
                        <div class="quick-search quick-search-dropdown" id="kt_quick_search_dropdown">
                            <!--begin:Form-->
                            <form method="get" class="quick-search-form">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <span class="svg-icon svg-icon-lg">
                                                <!--begin::Svg Icon | path:assets/media/svg/icons/General/Search.svg-->
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24" />
                                                        <path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                        <path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero" />
                                                    </g>
                                                </svg>
                                                <!--end::Svg Icon-->
                                            </span>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Search..." />
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="quick-search-close ki ki-close icon-sm text-muted"></i>
                                        </span>
                                    </div>
                                </div>
                            </form>
                            <!--end::Form-->
                            <!--begin::Scroll-->
                            <div class="quick-search-wrapper scroll" data-scroll="true" data-height="325" data-mobile-height="200"></div>
                            <!--end::Scroll-->
                        </div>
                    </div>
                    <!--end::Dropdown-->
                </div>
                <!--end::Search-->
                <!--begin::User-->
                <div class="topbar-item">
                    <div class="btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
                        <span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">Hi,</span>
                        <span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3">{{Auth::user()->name}}</span>
                        <span class="symbol symbol-lg-35 symbol-25 symbol-light-success">
                            <span class="symbol-label font-size-h5 font-weight-bold">S</span>
                        </span>
                    </div>
                </div>
                <!--end::User-->
            </div>
            <!--end::Topbar-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Header-->
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="">

        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container">
                <!--begin::Profile Personal Information-->
                <div class="d-flex flex-row">
                    <div class="d-flex flex-column-fluid">
                        <!--begin::Container-->
                        <div class="container">
                            <!--begin::Profile Personal Information-->
                            <div class="d-flex flex-row mt-5">
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
                                    <div class="card card-custom card-stretch mt-5">
                                        <!--begin::Header-->
                                        <div class="card-header py-3 mt-7">
                                            <div class="card-title align-items-start flex-column">
                                                <h3 class="card-label font-weight-bolder text-dark">Thông tin cá nhân</h3>
                                            </div>
                                            <div class="card-toolbar">
                                                <a href="{{route('profile.edit')}}" class="btn btn-success mr-2">Chỉnh sửa thông tin</a>
                                                <a href="{{route('changepassword.user.form')}}" class="btn btn-danger mr-2">Thay đổi mật khẩu</a>
                                            </div>
                                        </div>
                                        <!--end::Header-->
                                        <!--begin::Form-->
                                        <form class="form">
                                            <!--begin::Body-->
                                            <div class="card-body">

                                                <div class="row">
                                                    <label class="col-12"></label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <h5 class="font-weight-bold mb-6">Thông tin</h5>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-12 col-lg-3 col-form-label">Avatar</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="image-input image-input-outline" id="kt_profile_avatar" style="background-image: url(assets/media/users/blank.png)">
                                                            <div class="image-input-wrapper" style="background-image: url('{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('default-avatar.png') }}')"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-12 col-lg-3 col-form-label">Họ tên</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <input class="form-control form-control-lg form-control-solid" name="name" type="text" value="{{ $user->name }}" disabled/>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-12 col-lg-3 col-form-label">Số điện thoại</label>
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
                                                    <label class="col-12 col-lg-3 col-form-label">Email</label>
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
                                                    <label class="col-12 col-lg-3 col-form-label">Ngày sinh</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group input-group-lg input-group-solid">
                                                            <input type="date" class="form-control form-control-lg form-control-solid" name="dob" placeholder="Ngày sinh" value="{{ $user->dob ?? 'Chưa thiết lập' }}" disabled/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-12 col-lg-3 col-form-label">Địa chỉ</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <input class="form-control form-control-lg form-control-solid" name="address" type="text" value="{{ $user->address ?? 'Chưa thiết lập' }}" disabled/>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Body-->
                                        </form>

                                        <!--end::Form-->
                                    </div>
                                </div>
                            </div>
                            <!--end::Profile Personal Information-->
                        </div>
                        <!--end::Container-->
                    </div>
                </div>
                <!--end::Profile Personal Information-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
</div>
@endsection
