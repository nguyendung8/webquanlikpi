<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Phongban;
use App\Models\Quyen;
use App\Models\Nhatky;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->ID_quyen != 1) {
                abort(403, 'Bạn không có quyền truy cập trang này.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $perPage = $request->get('per_page', 10);

        // Lấy dữ liệu cho biểu đồ số lượng tài khoản theo thời gian tạo
        $usersByTime = User::select(
                DB::raw('DATE(Ngay_tao) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('Ngay_tao', date('Y'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Lấy dữ liệu cho biểu đồ tài khoản theo phòng ban
        $usersByDepartment = DB::table('users')
            ->join('phongban', 'users.ID_phongban', '=', 'phongban.ID_phongban')
            ->select('phongban.Ten_phongban', DB::raw('COUNT(*) as count'))
            ->groupBy('phongban.ID_phongban', 'phongban.Ten_phongban')
            ->get();

        // Lấy danh sách người dùng với tìm kiếm và phân trang
        $query = User::with(['quyen', 'phongban']);

        if ($search) {
            $query->where('Ho_ten', 'like', "%{$search}%")
                  ->orWhere('Email', 'like', "%{$search}%")
                  ->orWhereHas('quyen', function($q) use ($search) {
                      $q->where('Ten_quyen', 'like', "%{$search}%");
                  })
                  ->orWhereHas('phongban', function($q) use ($search) {
                      $q->where('Ten_phongban', 'like', "%{$search}%");
                  });
        }

        $users = $query->orderBy('ID_user', 'desc')->paginate($perPage);

        return view('admin.users.index', compact('usersByTime', 'usersByDepartment', 'users', 'search', 'perPage'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Ho_ten' => 'required|string|max:100',
            'Email' => 'required|email|unique:users,Email',
            'password' => 'required|string|min:3',
            'password_confirmation' => 'required|same:password',
            'ID_phongban' => 'required|exists:phongban,ID_phongban',
            'ID_quyen' => 'required|exists:quyen,ID_quyen',
            'Trang_thai' => 'required|in:hoat_dong,khong_hoat_dong,bi_chan'
        ]);

        $user = User::create([
            'Ho_ten' => $request->Ho_ten,
            'Email' => $request->Email,
            'MK' => $request->password,
            'MK_hash' => Hash::make($request->password),
            'ID_phongban' => $request->ID_phongban,
            'ID_quyen' => $request->ID_quyen,
            'Trang_thai' => $request->Trang_thai
        ]);

        // Ghi nhật ký thêm user
        Nhatky::create([
            'ID_nguoithuchien' => Auth::id(),
            'Doi_tuong' => 'user',
            'ID_doi_tuong' => $user->ID_user,
            'Hanh_dong' => 'them',
        ]);

        return response()->json(['success' => true, 'message' => '🎉 Thêm người dùng thành công!']);
    }

    public function show($id)
    {
        $user = User::with(['quyen', 'phongban'])->findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Lưu giá trị cũ để ghi nhật ký
        $oldValues = [
            'Ho_ten' => $user->Ho_ten,
            'Email' => $user->Email,
            'ID_phongban' => $user->ID_phongban,
            'ID_quyen' => $user->ID_quyen,
            'Trang_thai' => $user->Trang_thai
        ];

        // Validation rules
        $rules = [
            'Ho_ten' => 'required|string|max:100',
            'Email' => 'required|email|unique:users,Email,' . $id . ',ID_user',
            'ID_phongban' => 'required|exists:phongban,ID_phongban',
            'ID_quyen' => 'required|exists:quyen,ID_quyen',
            'Trang_thai' => 'required|in:hoat_dong,khong_hoat_dong,bi_chan'
        ];

        // Chỉ validate password nếu có nhập
        if ($request->password) {
            $rules['password'] = 'string|min:3';
            $rules['password_confirmation'] = 'same:password';
        }

        $request->validate($rules);

        $updateData = [
            'Ho_ten' => $request->Ho_ten,
            'Email' => $request->Email,
            'ID_phongban' => $request->ID_phongban,
            'ID_quyen' => $request->ID_quyen,
            'Trang_thai' => $request->Trang_thai
        ];

        // Chỉ update password nếu có nhập mật khẩu mới
        if ($request->password && !empty($request->password)) {
            $updateData['MK'] = $request->password;
            $updateData['MK_hash'] = Hash::make($request->password);
        }

        $user->update($updateData);

        // Lưu giá trị mới để ghi nhật ký
        $newValues = [
            'Ho_ten' => $user->Ho_ten,
            'Email' => $user->Email,
            'ID_phongban' => $user->ID_phongban,
            'ID_quyen' => $user->ID_quyen,
            'Trang_thai' => $user->Trang_thai
        ];

        // Ghi nhật ký sửa user
        Nhatky::create([
            'ID_nguoithuchien' => Auth::id(),
            'Doi_tuong' => 'user',
            'ID_doi_tuong' => $user->ID_user,
            'Hanh_dong' => 'sua',
        ]);

        return response()->json(['success' => true, 'message' => '✨ Cập nhật thông tin người dùng thành công!']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Không cho phép xóa chính mình
        if ($user->ID_user == Auth::id()) {
            return response()->json(['success' => false, 'message' => '❌ Không thể xóa chính mình!']);
        }

        // Lưu thông tin user trước khi xóa để ghi nhật ký
        $userInfo = [
            'Ho_ten' => $user->Ho_ten,
            'Email' => $user->Email,
            'ID_phongban' => $user->ID_phongban,
            'ID_quyen' => $user->ID_quyen,
            'Trang_thai' => $user->Trang_thai
        ];

        // Ghi nhật ký xóa user
        Nhatky::create([
            'ID_nguoithuchien' => Auth::id(),
            'Doi_tuong' => 'user',
            'ID_doi_tuong' => $user->ID_user,
            'Hanh_dong' => 'xoa',
        ]);

        $user->delete();

        return response()->json(['success' => true, 'message' => '🗑️ Xóa người dùng thành công!']);
    }
}
