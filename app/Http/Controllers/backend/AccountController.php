<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\User as UserResource;
use App\User;

class AccountController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view_accounts', ['only' => ['index', 'show']]);
        $this->middleware('permission:add_accounts', ['only' => ['store']]);
        $this->middleware('permission:edit_accounts', ['only' => ['update']]);
        $this->middleware('permission:delete_accounts', ['only' => ['destroy']]);
    }

    public function index()
    {
        $rows = UserResource::collection(User::fetchData(request()->all()));
        $data = ['rows' => $rows, 'paginate' => $this->paginate($rows)];
        return response()->json(['data' => $data], 200);
    }

    public function store()
    {
        $row = User::createOrUpdate(NULL, request()->all(), NULL);
        if($row === true) {
            return response()->json(['message' => ''], 201);
        } else {
            return response()->json(['message' => 'Unable to create entry ' . $row], 500);
        }
    }

    public function show($id)
    {
        $row = new UserResource(User::findOrFail($id));
        return response()->json(['row' => $row], 200);
    }

    public function update($id)
    {
        $row = User::createOrUpdate($id, request()->all(), NULL);
        if($row === true) {
            return response()->json(['message' => ''], 200);
        } else {
            return response()->json(['message' => 'Unable to update entry ' . $row], 500);
        }
    }

    public function destroy($id)
    {
        $row = User::destroy($id);
        if($row) {
            return response()->json(['message' => ''], 200);
        } else {
            return response()->json(['message' => 'Unable to delete entry'], 500);
        }
    }
}
