<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Social as SocialResource;
use App\Social;

class SocialController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view_socials', ['only' => ['index', 'show']]);
        $this->middleware('permission:add_socials', ['only' => ['store']]);
        $this->middleware('permission:edit_socials', ['only' => ['update']]);
        $this->middleware('permission:delete_socials', ['only' => ['destroy']]);
    }

    public function index()
    {
        $rows = SocialResource::collection(Social::fetchData(request()->all()));
        $data = ['rows' => $rows, 'paginate' => $this->paginate($rows)];
        return response()->json(['data' => $data], 200);
    }

    public function store()
    {
        $row = Social::createOrUpdate(NULL, request()->all());
        if($row === true) {
            return response()->json(['message' => ''], 201);
        } else {
            return response()->json(['message' => 'Unable to create entry ' . $row], 500);
        }
    }

    public function show($id)
    {
        $row = new SocialResource(Social::findOrFail($id));
        return response()->json(['row' => $row], 200);
    }

    public function update($id)
    {
        $row = Social::createOrUpdate($id, request()->all());
        if($row === true) {
            return response()->json(['message' => ''], 200);
        } else {
            return response()->json(['message' => 'Unable to update entry ' . $row], 500);
        }
    }

    public function destroy($id)
    {
        $row = Social::destroy($id);
        if($row) {
            return response()->json(['message' => ''], 200);
        } else {
            return response()->json(['message' => 'Unable to delete entry'], 500);
        }
    }
}
