<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Permission as PermissionResource;
use App\Permission;

class PermissionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view_permissions', ['only' => ['index', 'show']]);
        $this->middleware('permission:add_permissions', ['only' => ['store']]);
        $this->middleware('permission:edit_permissions', ['only' => ['update']]);
        $this->middleware('permission:delete_permissions', ['only' => ['destroy']]);
    }

    public function index()
    {
        $rows = PermissionResource::collection(Permission::fetchData(request()->all()));
        $data = ['rows' => $rows, 'paginate' => $this->paginate($rows)];
        return response()->json(['data' => $data], 200);
    }

    public function store()
    {
        $row = Permission::createOrUpdate(NULL, request()->all(), NULL);
        if($row === true) {
            return response()->json(['message' => ''], 201);
        } else {
            return response()->json(['message' => 'Unable to create entry ' . $row], 500);
        }
    }

    public function show($id)
    {
        $row = new PermissionResource(Permission::findOrFail($id));
        return response()->json(['row' => $row], 200);
    }

    public function update($id)
    {
        $row = Permission::createOrUpdate($id, request()->all(), NULL);
        if($row === true) {
            return response()->json(['message' => ''], 200);
        } else {
            return response()->json(['message' => 'Unable to update entry ' . $row], 500);
        }
    }

    public function destroy($id)
    {
        $row = Permission::destroy($id);
        if($row) {
            return response()->json(['message' => ''], 200);
        } else {
            return response()->json(['message' => 'Unable to delete entry'], 500);
        }
    }
}
