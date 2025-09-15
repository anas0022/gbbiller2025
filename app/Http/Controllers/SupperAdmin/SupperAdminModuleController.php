<?php

namespace App\Http\Controllers\SupperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // <-- add this
use App\Models\SupperAdmin\Menu\SuperAdminModules;

class SupperAdminModuleController extends Controller
{
    public function StoreModule(Request $request)
    {
        try {

            $isUpdate = !empty($request->id);


            $rules = [
                'modulename' => 'required|string|max:255',
                'icon' => [
                    'required',
                    'string',
                    'max:255',
                    function ($attribute, $value, $fail) {
                        // Simple regex to check FA class (e.g., "fa fa-id-card" or "fas fa-user")
                        if (!preg_match('/^fa[srlb]? fa-[\w-]+$/', trim($value))) {
                            $fail('The ' . $attribute . ' must be a valid Font Awesome icon class.');
                        }
                    }
                ],
            ];

            if ($isUpdate) {
                // Ignore the current record when checking uniqueness
                $rules['modulename'] .= '|unique:super_admin_modules,modulename,' . $request->id;
            } else {
                $rules['modulename'] .= '|unique:super_admin_modules,modulename';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            if ($isUpdate) {
                // Update only modulename and icon
                $module = SuperAdminModules::findOrFail($request->id);
                $module->update($validator->validated());
                $message = 'Module updated successfully';
            } else {
                // Create new module
                SuperAdminModules::create($validator->validated());
                $message = 'Module added successfully';
            }

            return response()->json([
                'status' => true,
                'message' => $message
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function superadminmenu()
    {
        $menus = SuperAdminModules::all();

        return response()->json($menus);
    }
    public function updateStatus(Request $request)
    {


        $module = SuperAdminModules::findOrFail($request->id);
        $module->status = $request->status;
        $module->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'status' => $module->status,
        ]);
    }

    public function deleteModule($id)
    {
        try {
            $module = SuperAdminModules::findOrFail($id);
            $module->delete();

            return response()->json([
                'status' => true,
                'message' => 'Module deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
