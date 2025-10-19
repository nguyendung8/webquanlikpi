<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Thongbao;

class NotificationController extends Controller
{
    public function getNotifications(Request $request)
    {
        $userId = Auth::id();
        $page = $request->get('page', 1);
        $perPage = 10;

        $notifications = Thongbao::with('nguoigui')
            ->where('ID_nguoinhan', $userId)
            ->orderBy('Ngay_gui', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $notifications->items(),
            'current_page' => $notifications->currentPage(),
            'last_page' => $notifications->lastPage(),
            'has_more' => $notifications->hasMorePages(),
            'total' => $notifications->total()
        ]);
    }

    public function markAsRead($id)
    {
        $userId = Auth::id();
        Thongbao::where('ID_thongbao', $id)
            ->where('ID_nguoinhan', $userId)
            ->update(['Da_xem' => 1]);

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        $userId = Auth::id();
        Thongbao::where('ID_nguoinhan', $userId)
            ->where('Da_xem', false)
            ->update(['Da_xem' => true]);

        return response()->json(['success' => true]);
    }

    public function getUnreadCount()
    {
        $userId = Auth::id();
        $count = Thongbao::where('ID_nguoinhan', $userId)
            ->where('Da_xem', 0)
            ->count();

        return response()->json(['count' => $count]);
    }
}
