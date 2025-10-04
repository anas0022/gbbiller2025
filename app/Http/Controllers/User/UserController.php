<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auth\UserModel;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function userCreate(Request $request)
    {
        try {
            $isUpdate = !empty($request->id);

            // find the last biller_code (if exists)
            $lastUser = UserModel::latest('id')->first();
            $nextBillerCode = $lastUser ? $lastUser->biller_code + 1 : 1;

            // Validation rules
            $rules = [
                "name"  => "required|string|max:255",
                "email" => [
                    "required",
                    "string",
                    "max:255",
                    $isUpdate
                        ? Rule::unique('user_models', 'email')->ignore($request->id)
                        : Rule::unique('user_models', 'email')
                ],
            ];
              
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation errors',
                    'errors'  => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();
            $data['password'] = Hash::make("pass" . $nextBillerCode); // secure password
            $data['biller_code'] = $nextBillerCode;

            if ($isUpdate) {
                $user = UserModel::findOrFail($request->id);
                $user->update($data);
                $message = 'User updated successfully';
            } else {
                UserModel::create($data);
                $message = 'User created successfully';
            }

            return response()->json([
                'status'  => true,
                'message' => $message
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong. Please try again later.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
