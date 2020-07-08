<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Tag as TagResource;
use App\Tag;

class TagController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view_tags', ['only' => ['index', 'show']]);
        $this->middleware('permission:add_tags', ['only' => ['store']]);
        $this->middleware('permission:edit_tags', ['only' => ['update']]);
        $this->middleware('permission:delete_tags', ['only' => ['destroy']]);
    }

    public function index()
    {
        $rows = TagResource::collection(Tag::fetchData(request()->all()));
        $data = ['rows' => $rows, 'paginate' => $this->paginate($rows)];
        return response()->json(['data' => $data], 200);
    }

    public function store()
    {
        $row = Tag::createOrUpdate(NULL, request()->all());
        if($row === true) {
            return response()->json(['message' => ''], 201);
        } else {
            return response()->json(['message' => 'Unable to create entry ' . $row], 500);
        }
    }

    public function show($id)
    {
        $row = new TagResource(Tag::findOrFail($id));
        return response()->json(['row' => $row], 200);
    }

    public function update($id)
    {
        $row = Tag::createOrUpdate($id, request()->all());
        if($row === true) {
            return response()->json(['message' => ''], 200);
        } else {
            return response()->json(['message' => 'Unable to update entry ' . $row], 500);
        }
    }

    public function destroy($id)
    {
        $row = Tag::destroy($id);
        if($row) {
            return response()->json(['message' => ''], 200);
        } else {
            return response()->json(['message' => 'Unable to delete entry'], 500);
        }
    }
}
