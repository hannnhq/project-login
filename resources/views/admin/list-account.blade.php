
@extends('admin.layout.app')
@section('title','Danh sách tài khoản')
@section('page-title','Danh sách tài khoản')
@section('content')
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Danh sách</h3>
                </div>
            </div>
            <div class="card-body">
                @if ($errors->has('message'))
                    <div class="alert alert-danger">{{ $errors->first('message') }}</div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">{{session('success')}}</div>
                @endif
                <!--begin: Search Form-->
                <form action="{{route('admin.list-account')}}" method="get">
                <div class="mb-7">
                    <div class="row align-items-center">
                        <div class="col-lg-9 col-xl-8">
                            <div class="row align-items-center">
                                <div class="col-md-4 my-2 my-md-0">
                                    <div class="input-icon">
                                        <input type="text" class="form-control" placeholder="Tìm theo tên hoặc email" value="{{request('search')}}" id="" name="search" />
                                        <span>
                                            <i class="flaticon2-search-1 text-muted"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 my-2 my-md-0">
                                    <div class="d-flex align-items-center">
                                        <label class="mr-3 mb-0 d-none d-md-block">Vai trò:</label>
                                        <select name="role" class="form-control" id="">
                                            <option value="">All</option>
                                            <option value="admin" {{request('role') === 'admin' ? 'selected' : ''}}>Admin</option>
                                            <option value="user" {{request('role') === 'user' ? 'selected' : ''}}>Khách hàng</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 my-2 my-md-0">
                                    <div class="d-flex align-items-center">
                                        <label class="mr-3 mb-0 d-none d-md-block">Trạng thái:</label>
                                        <select name="is_active" class="form-control" id="">
                                            <option value="">All</option>
                                            <option value="1" {{request('is_active') === '1' ? 'selected' : ''}}>Hoạt động</option>
                                            <option value="0" {{request('is_active') === '0' ? 'selected' : ''}}>Bị khoá</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-xl-4 mt-5 mt-lg-0">
                            <button type="submit" class="btn btn-light-primary px-6 font-weight-bold">Tìm kiếm</button>
                        </div>
                    </div>
                </div>
                </form>

                <table class="table table-striped">
                    <thead>
                        <th>STT</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Ngày tạo</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </thead>
                    <tbody class="table-group-divider">
                        @forelse ($listAccount as $index => $user)
                        <tr>
                            <td>{{ $listAccount->firstItem() + $index }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role }}</td>
                            <td>{{ $user->created_at->format('d/m/Y H:i:s') }}</td>
                            <td>
                                <span class="badge {{ $user->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{$user->is_active ? 'Hoạt động' : 'Bị khoá'}}
                                </span>
                            </td>
                            <td>
                                @if ($user->is_active)
                                    <button type="button" class="btn btn-sm btn-danger"
                                    onclick="confirmAction({{$user->id}}, '{{$user->email}}', 'lock')">Khoá</button>
                                @else
                                    <button type="button" class="btn btn-sm btn-success"
                                    onclick="confirmAction({{$user->id}}, '{{$user->email}}', 'unlock')">Kích hoạt
                                    </button>
                                @endif
                                <a href="{{route('admin.detail-account', $user->id)}}" class="btn btn-sm btn-warning">Xem</a>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-warning font-weight-bold">Không tìm thấy người dùng</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <form id="statusForm" method="POST" style="display: none;">
                    @csrf
                    <input type="hidden" name="action" id="actionInput">
                </form>

                {{-- Phân trang --}}
                <div class="mt-3 d-flex justify-content-center">
                    {{$listAccount->withQueryString()->links()}}
                </div>
                <!--end::Search Form-->
                <!--begin: Datatable-->
                <div class="datatable datatable-bordered datatable-head-custom" id="">

                </div>
                <!--end: Datatable-->
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
<style>
    .swal2-icon {
        margin: 0 auto !important;
    }
</style>

<script>
    function confirmAction(userId, email, actionType){
        let config = {
            lock: {
                title: 'Xác nhận khoá tài khoản',
                message: `Bạn có chắc chắn muốn xoá tài khoản ${email} không?`,
                actionUrl: `/admin/account/${userId}/lock`,
                buttonClass: 'btn btn-danger',
                icon: 'warning'
            },
            unlock:{
                title: 'Xác nhận kích hoạt tài khoản',
                message: `Bạn có chắc chắn muốn kích hoạt lại tài khoản ${email} không?`,
                actionUrl: `/admin/account/${userId}/unlock`,
                buttonClass: 'btn btn-success',
                icon: 'question'
            }
        };
        Swal.fire({
            title: config[actionType].title,
            html: config[actionType].message,
            icon: config[actionType].icon,
            showCancelButton: true,
            confirmButtonText: 'Xác nhận',
            cancelButtonText: 'Huỷ',
            customClass: {
                confirmButton: config[actionType].buttonClass,
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false, // Vô hiệu hóa styling mặc định của SweetAlert
        }).then((result) => {
            if(result.isConfirmed){
                const form = document.getElementById('statusForm');
                form.action = config[actionType].actionUrl;
                document.getElementById('actionInput').value = actionType;
                form.submit();
            }
        });
    }
</script>
@endsection
