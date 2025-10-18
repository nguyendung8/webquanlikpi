<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Nhatky;
use App\Models\User;
use App\Models\Kpi;
use App\Models\PhancongKpi;

class ActivityController extends Controller
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
        $action = $request->get('action');
        $object = $request->get('object');

        $query = Nhatky::with('nguoiThucHien');

        // Lọc theo hành động
        if ($action) {
            $query->where('Hanh_dong', $action);
        }

        // Lọc theo đối tượng
        if ($object) {
            $query->where('Doi_tuong', $object);
        }

        // Tìm kiếm theo tên người thực hiện hoặc đối tượng
        if ($search) {
            $query->whereHas('nguoiThucHien', function($q) use ($search) {
                $q->where('Ho_ten', 'like', "%{$search}%");
            });
        }

        $activities = $query->orderBy('Ngay_thuchien', 'desc')->paginate($perPage);

        // Thống kê tổng quan
        $stats = [
            'total' => Nhatky::count(),
            'them' => Nhatky::where('Hanh_dong', 'them')->count(),
            'sua' => Nhatky::where('Hanh_dong', 'sua')->count(),
            'xoa' => Nhatky::where('Hanh_dong', 'xoa')->count(),
            'duyet' => Nhatky::where('Hanh_dong', 'duyet')->count(),
        ];

        return view('admin.activity.index', compact('activities', 'search', 'perPage', 'action', 'object', 'stats'));
    }
}
