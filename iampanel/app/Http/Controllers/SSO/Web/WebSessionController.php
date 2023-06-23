<?php

namespace App\Http\Controllers\SSO\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebSessionController extends Controller
{
    public function changeRoleActive(Request $request)
    {
        auth('imissu-web')->user()->changeRoleActive($request->role_active);
        if (auth('imissu-web')->user()->role_active === $request->role_active) {
            return response()->json([
                'message' => 'Mengubah peran aktif',
                'code' => 200
            ], 200);
        } else {
            return response()->json([
                'message' => 'Gagal mengubah peran aktif',
                'code' => 403
            ], 403);
        }
    }
}