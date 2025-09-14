<?php

namespace App\Http\Controllers\SupperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupperAdmin\Menu\SuperAdminSubmenu;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Validation\Rule;

class SupperAdminSubMenuController extends Controller
{
     public function create_menu(Request $request)
    {
        try {

            $isUpdate = !empty($request->id);


            $rules = [
                'menu_module' => 'required',
                'menuname' => ['required', 'string', 'max:255'],
                'menu_id'=> 'required',
                'sub_route' => ['required', 'regex:/^\/.+$/', 'max:255'],


            ];

            if ($isUpdate) {
                $rules['menuname'][] = Rule::unique('super_admin_submenus', 'menuname')->ignore($request->id);
            } else {
                $rules['menuname'][] = Rule::unique('super_admin_submenus', 'menuname');
                
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
                $module = SuperAdminSubmenu::findOrFail($request->id);
                $module->update($validator->validated());
                $message = 'Menuname updated successfully';
            } else {
                // Create new module
                SuperAdminSubmenu::create($validator->validated());
                $message = 'Menuname added successfully';
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
    public function updateStatus(Request $request)
    {
       

        $module = SuperAdminSubmenu::findOrFail($request->id);
        $module->status = $request->status;
        $module->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'status'  => $module->status,
        ]);
    }
public function deleteMenu($id)
    {
        try {
            $module = SuperAdminSubmenu::findOrFail($id);
            $module->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Module deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong. Please try again later.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
