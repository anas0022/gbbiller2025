<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Auth\UserModel;


class AuthController extends Controller
{
   public function login(Request $request)
{
    // Validate input
    $validator = Validator::make($request->all(), [
        'biller_code' => 'required|string',
        'password'    => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status'  => false,
            'message' => 'Validation errors',
            'errors'  => $validator->errors()
        ], 422);
    }

    $credentials = $request->only('biller_code', 'password');

    // Special case: BILL000 restricted to your IP
    if ($credentials['biller_code'] === 'BILL000') {
     

        $allowedIp = '127.0.0.1';

        if ($request->ip() !== $allowedIp) {
            return response()->json([
                'status'  => false,
                'message' => 'This device is not authorized'
            ], 401);
        }

        // Attempt authentication
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response()->json([
                'status'   => true,
                'message'  => 'Login successful',
                'redirect' => 'superadmin/dashboard'
            ], 200);
        }

        return response()->json([
            'status'  => false,
            'message' => 'Invalid credentials'
        ], 401);
    }

    // Normal authentication for other biller_code
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        return response()->json([
            'status'   => true,
            'message'  => 'Login successful',
            'redirect' => 'admin/dashboard'
        ], 200);
    }

    return response()->json([
        'status'  => false,
        'message' => 'Invalid credentials'
    ], 401);
}

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
