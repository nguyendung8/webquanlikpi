<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Phongban;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PhongbanController extends Controller
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
        
        // Lấy dữ liệu cho biểu đồ thống kê user theo phòng ban
        $usersByDepartment = DB::table('users')
            ->join('phongban', 'users.ID_phongban', '=', 'phongban.ID_phongban')
            ->select('phongban.Ten_phongban', DB::raw('COUNT(*) as count'))
            ->groupBy('phongban.ID_phongban', 'phongban.Ten_phongban')
            ->get();

        // Lấy danh sách phòng ban với tìm kiếm và phân trang
        $query = Phongban::withCount('users');
        
        if ($search) {
            $query->where('Ten_phongban', 'like', "%{$search}%");
        }

        $phongbans = $query->orderBy('ID_phongban', 'desc')->paginate($perPage);

        return view('admin.phongban.index', compact('usersByDepartment', 'phongbans', 'search', 'perPage'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Ten_phongban' => 'required|string|max:100|unique:phongban,Ten_phongban'
        ]);

        Phongban::create([
            'Ten_phongban' => $request->Ten_phongban
        ]);

        return response()->json(['success' => true, 'message' => '🎉 Thêm phòng ban thành công!']);
    }

    public function show($id)
    {
        $phongban = Phongban::findOrFail($id);
        return response()->json($phongban);
    }

    public function update(Request $request, $id)
    {
        $phongban = Phongban::findOrFail($id);
        
        $request->validate([
            'Ten_phongban' => 'required|string|max:100|unique:phongban,Ten_phongban,' . $id . ',ID_phongban'
        ]);

        $phongban->update([
            'Ten_phongban' => $request->Ten_phongban
        ]);

        return response()->json(['success' => true, 'message' => '✨ Cập nhật phòng ban thành công!']);
    }

    public function destroy($id)
    {
        $phongban = Phongban::findOrFail($id);
        
        // Kiểm tra xem có user nào thuộc phòng ban này không
        if ($phongban->users()->count() > 0) {
            return response()->json(['success' => false, 'message' => '❌ Không thể xóa phòng ban này vì còn có người dùng!']);
        }
        
        $phongban->delete();
        
        return response()->json(['success' => true, 'message' => '🗑️ Xóa phòng ban thành công!']);
    }
}
