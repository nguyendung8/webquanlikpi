<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->ID_quyen;

        // 1 = Admin, 2 = Quản lý, 3 = Nhân viên
        $roleMap = [
            'admin' => 1,
            'manager' => 2,
            'employee' => 3
        ];

        foreach ($roles as $role) {
            if (isset($roleMap[$role]) && $userRole == $roleMap[$role]) {
                return $next($request);
            }
        }

        abort(403, 'Bạn không có quyền truy cập trang này.');
    }
}
