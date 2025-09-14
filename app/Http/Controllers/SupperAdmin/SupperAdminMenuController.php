<?php

namespace App\Http\Controllers\SupperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupperAdmin\Menu\SuperAdminMenu;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
class SupperAdminMenuController extends Controller
{
    public function create_menu(Request $request)
    {
        try {

            $isUpdate = !empty($request->id);


            $rules = [
                'Module_id' => 'required',
                'Menuname' => ['required', 'string', 'max:255'],
                'route' => ['required', 'regex:/^\/.+$/', 'max:255'],


            ];

            if ($isUpdate) {
                $rules['Menuname'][] = Rule::unique('super_admin_menus', 'Menuname')->ignore($request->id);
            } else {
                $rules['Menuname'][] = Rule::unique('super_admin_menus', 'Menuname');
                
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
                $module = SuperAdminMenu::findOrFail($request->id);
                $module->update($validator->validated());
                $message = 'Menuname updated successfully';
            } else {
                // Create new module
                SuperAdminMenu::create($validator->validated());
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
       

        $module = SuperAdminMenu::findOrFail($request->id);
        $module->status = $request->status;
        $module->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'status'  => $module->status,
        ]);
    }

      public function deleteModule($id)
    {
        try {
            $module = SuperAdminMenu::findOrFail($id);
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
