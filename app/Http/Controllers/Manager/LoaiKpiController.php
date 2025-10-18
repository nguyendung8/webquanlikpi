<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LoaiKpi;

class LoaiKpiController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->ID_quyen != 2) {
                abort(403, 'Bạn không có quyền truy cập trang này.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $perPage = $request->get('per_page', 10);

        $query = LoaiKpi::withCount('kpis');

        if ($search) {
            $query->where('Ten_loai_kpi', 'like', "%{$search}%");
        }

        $loaiKpis = $query->orderBy('ID_loai_kpi', 'desc')->paginate($perPage);

        return view('manager.loaikpi.index', compact('loaiKpis', 'search', 'perPage'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Ten_loai_kpi' => 'required|string|max:100|unique:loai_kpi,Ten_loai_kpi'
        ]);

        LoaiKpi::create([
            'Ten_loai_kpi' => $request->Ten_loai_kpi
        ]);

        return response()->json(['success' => true, 'message' => '🎉 Thêm loại KPI thành công!']);
    }

    public function show($id)
    {
        $loaiKpi = LoaiKpi::findOrFail($id);
        return response()->json($loaiKpi);
    }

    public function update(Request $request, $id)
    {
        $loaiKpi = LoaiKpi::findOrFail($id);

        $request->validate([
            'Ten_loai_kpi' => 'required|string|max:100|unique:loai_kpi,Ten_loai_kpi,' . $id . ',ID_loai_kpi'
        ]);

        $loaiKpi->update([
            'Ten_loai_kpi' => $request->Ten_loai_kpi
        ]);

        return response()->json(['success' => true, 'message' => '✨ Cập nhật loại KPI thành công!']);
    }

    public function destroy($id)
    {
        $loaiKpi = LoaiKpi::findOrFail($id);

        // Kiểm tra xem có KPI nào thuộc loại này không
        if ($loaiKpi->kpis()->count() > 0) {
            return response()->json(['success' => false, 'message' => '❌ Không thể xóa loại KPI này vì còn có KPI thuộc loại này!']);
        }

        $loaiKpi->delete();

        return response()->json(['success' => true, 'message' => '🗑️ Xóa loại KPI thành công!']);
    }
}
