<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions_parent = Permission::where('parent_id', 0)->get();
     
        return view('admin.pages.permission.index', compact('permissions_parent'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   $permissions_parent = Permission::where('parent_id', 0)->get();
        return view('admin.pages.permission.create',compact('permissions_parent'));
    }

    public function store(Request $request)
    {
        // validator
        // validator
        $rules = [
            'module_parent' => 'required',
          
        ];
        $messages = [
            'module_parent.required' => 'Mời chọn tên module!',
            'desc.required' => 'Mời nhập mô tả module!',
           
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $permission = Permission::create([
            'name' => $request->module_parent,
            'desc' => $request->desc,
            'parent_id' => 0,
            'key_code' => ''
        ]);
        
        foreach (config('permissions.permission_childen') as $value_module) {
           
            Permission::create([
                'name' => $value_module,
                'desc' => $value_module,
                'parent_id' => $permission->id,
                'key_code' => $request->module_parent . '_' . $value_module,

            ]);
        }
        return \redirect()->route('admin.permission.index')->with('status', 'Thêm mới permission thành công !');
    }

    public function show(Permission $permission)
    {
        //
    }

    public function edit($id, Permission $permission)
    {   $permissions_parent = Permission::where('parent_id', 0)->get();
        $permissions_id = $permission->find($id);
        $permissionsChecked = $permissions_id->permissionsChilden;
        //    $selec= Permission::where('parent_id',0)->get();
        //     dd($selec);
        return view('admin.pages.permission.edit', compact('permissions_id', 'permissionsChecked','permissions_parent'));
    }

    public function update($id, Request $request, Permission $permission)
    {
        // validator
        $rules = [
            'module_parent' => 'required',
         
        ];
        $messages = [
            'module_parent.required' => 'Mời chọn tên module!',
            
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $permission = $permission->find($id)->update([
            'name' => $request->module_parent,
            'desc' => $request->desc,
            'parent_id' => 0,
            'key_code' => ''
        ]);
        return \redirect()->route('admin.permission.index')->with('status', 'Cập nhật permission thành công !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Permission $permission)
    {
        Permission::where('id', $id)->delete();
        Permission::where('parent_id', $id)->delete();
        return redirect()->back()->with('status', 'Xóa thành công permission thành công !');
    }
}
