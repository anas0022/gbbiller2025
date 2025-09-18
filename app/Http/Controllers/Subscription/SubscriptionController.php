<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\SupperAdmin\Subscription\SubscriptionModules;

class SubscriptionController extends Controller
{
    public function Storesub(Request $request)
    {
        try {
            $isUpdate = !empty($request->id);

            $rules = [
                'Sub_type' => ['required'],
                'icon'     => ['required'],
            ];

            if ($isUpdate) {
                $rules['sub_type'][] = Rule::unique('subscription_modules', 'sub_type')->ignore($request->id);
                $rules['icon'][]     = Rule::unique('subscription_modules', 'icon')->ignore($request->id);
            } else {
                $rules['sub_type'][] = Rule::unique('subscription_modules', 'sub_type');
                $rules['icon'][]     = Rule::unique('subscription_modules', 'icon');
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation errors',
                    'errors'  => $validator->errors()
                ], 422);
            }

            if ($isUpdate) {
                $module = SubscriptionModules::findOrFail($request->id);
                $module->update($validator->validated());
                $message = 'Subscription updated successfully';
            } else {
                SubscriptionModules::create($validator->validated());
                $message = 'Subscription added successfully';
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
