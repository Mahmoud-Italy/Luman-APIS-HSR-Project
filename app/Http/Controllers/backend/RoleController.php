<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Role as RoleResource;
use App\Role;

class RoleController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view_roles', ['only' => ['index', 'show']]);
        $this->middleware('permission:add_roles', ['only' => ['store']]);
        $this->middleware('permission:edit_roles', ['only' => ['update']]);
        $this->middleware('permission:delete_roles', ['only' => ['destroy']]);
    }

    public function index()
    {
        $rows = RoleResource::collection(Role::fetchData(request()->all()));
        $data = ['rows' => $rows, 'paginate' => $this->paginate($rows)];
        return response()->json(['data' => $data], 200);
    }

    public function store()
    {
        $row = Role::createOrUpdate(NULL, request()->all());
        if($row === true) {
            return response()->json(['message' => ''], 201);
        } else {
            return response()->json(['message' => 'Unable to create entry ' . $row], 500);
        }
    }

    public function show($id)
    {
        $row = new RoleResource(Role::findOrFail($id));
        return response()->json(['row' => $row], 200);
    }

    public function update($id)
    {
        $row = Role::createOrUpdate($id, request()->all());
        if($row === true) {
            return response()->json(['message' => ''], 200);
        } else {
            return response()->json(['message' => 'Unable to update entry ' . $row], 500);
        }
    }

    public function destroy($id)
    {
        $row = Role::destroy($id);
        if($row) {
            return response()->json(['message' => ''], 200);
        } else {
            return response()->json(['message' => 'Unable to delete entry'], 500);
        }
    }
}
