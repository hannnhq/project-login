<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index(){
        $user = Auth::user();
        return view('admin.dashboard', compact('user'));
    }

    public function showProfile(){
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    public function updateProfile(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|numeric',
            'dob' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpg,png|max:2048'
        ],[
            'phone.numeric' => 'Số điện thoại không được chứa chữ',
            'name.required' => 'Tên không được để trống',
            'avatar.image' => 'Tệp tải lên phải là hình ảnh',
            'avatar.mimes' => 'Chỉ cho phép ảnh định dạng JPG hoặc PNG',
            'avatar.max' => 'Kích thước ảnh tối đa là 2MB',
        ]);

        $user = Auth::user();

        if($request->hasFile('avatar') && $request->file('avatar')->isValid()){
            $file = $request->file('avatar');
            $filename = time(). '.'. $file->getClientOriginalExtension();

            // Lưu dường dẫn vào storage/app/public/avatars
            $path = $file->storeAs('avatars', $filename, 'public');
            $user->avatar = $path; // Lưu đường dẫn vào db
        }

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->dob = $request->dob;
        $user->address = $request->address;
        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'Cập nhật thông tin thành công');

    }

    public function listAccount(Request $request){
        try {
            $query = User::query();

            // Tìm kiếm theo tên hoặc email
            if($search = $request->input('search')){
                $query->where(function($q) use ($search){
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                });
            }

            // Lọc theo vai trò
            if ($role = $request->input('role')){
                $query->where('role', $role);
            }

            // Lọc theo trạng thái
            if(!is_null($request->input('is_active'))){
                $query->where('is_active', $request->input('is_active'));
            }

            $listAccount = $query->paginate(10);

            return view('admin.list-account', compact('listAccount'));
        } catch (\Exception $e) {
            return view('admin.list-account')->with('error','Không thể tải dữ liệu, vui lòng thử lại');
        }

    }

    public function updateAccountStatus(Request $request,$id){
        try {
            $admin = Auth::user();
            $action = $request->input('action'); //lock hoặc unlock

            Log::info("Gọi tới route updateAccountStatus với id = $id, action = $action");

            // KHông cho khoá chính mình
            if($admin->id == $id){
                return back()->withErrors(['message' => 'Không thể khoá tài khoản đang đăng nhập']);
            }
            // Kiểm tra quyền
            if($admin->role !== 'admin'){
                abort(403, 'Bạn không có quyền thực hiện hành động này');
            }

            $user = User::find($id);

            if(!$user){
                return back()->withErrors(['message'=>'Tài khoản không tồn tại']);
            }

            if($action === 'lock'){
                if($user->is_active == 0){
                    return back()->withErrors(['message'=>'Tài khoản đã bị khoá trước đó']);
                }
                $user->is_active = 0;
                $message = 'Khoá tài khoản thành công';
                $logMessage = "Admin {$admin->email} đã khoá tài khoản {$user->email} vào lúc ". now();
            }elseif($action === 'unlock'){
                if($user->is_active === 1){
                    return back()->withErrors(['message'=>'Tài khoản đang hoạt động']);
                }
                $user->is_active = 1;
                $message = 'Kích hoạt tài khoản thành công';
                $logMessage = "Admin {$admin->email} đã kích hoạt tài khoản {$user->email} vào lúc ". now();
            }else {
                return back()->withErrors(['message'=>'Hành động không hợp lệ']);
            }

            $user->Save();

            // Ghi Log hành động
            Log::channel('security')->info($logMessage);

            return back()->with('success',$message);
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Thao tác không thành công, vui lòng thử lại']);
        }
    }

    public function detailAccount($id){
        $user = User::find($id);
        if(!$user){
            return redirect()->back()->withErrors(['message'=>'Không tìm thấy tài khoản']);
        }
        return view('admin.detail-account', compact('user'));
    }

    public function destroy($id){
        Log::info("Gọi tới tài khoản id->$id");

        $user = User::findOrFail($id);
        // Xoá ảnh
        if($user->avatar && Storage::disk('public')->exists($user->avatar)){
            Storage::disk('public')->delete($user->avatar);
        }

        // KHông cho admin tự xoá mình
        if(Auth::id() == $user->id){
            Log::warning("Admin đang xoá chính mình id = $id");
            return back()->withErrors(['message' => 'Không thể tự xoá mình']);
        }

        $user->delete();
        Log::info("Đã xoá tài khoản id=$id thành công");
        return back()->with('success','Xoá thành công');
    }
}
