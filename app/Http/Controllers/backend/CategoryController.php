<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Category as CategoryResource;
use App\Category;

class CategoryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view_categories', ['only' => ['index', 'show']]);
        $this->middleware('permission:add_categories', ['only' => ['store']]);
        $this->middleware('permission:edit_categories', ['only' => ['update']]);
        $this->middleware('permission:delete_categories', ['only' => ['destroy']]);
    }

    public function index()
    {
        $rows = CategoryResource::collection(Category::fetchData(request()->all()));
        $data = ['rows' => $rows, 'paginate' => $this->paginate($rows)];
        return response()->json(['data' => $data], 200);
    }

    public function store()
    {
        $row = Category::createOrUpdate(NULL, request()->all(), NULL);
        if($row === true) {
            return response()->json(['message' => ''], 201);
        } else {
            return response()->json(['message' => 'Unable to create entry ' . $row], 500);
        }
    }

    public function show($id)
    {
        $row = new CategoryResource(Category::findOrFail($id));
        return response()->json(['row' => $row], 200);
    }

    public function update($id)
    {
        $row = Category::createOrUpdate($id, request()->all(), NULL);
        if($row === true) {
            return response()->json(['message' => ''], 200);
        } else {
            return response()->json(['message' => 'Unable to update entry ' . $row], 500);
        }
    }

    public function destroy($id)
    {
        $row = Category::destroy($id);
        if($row) {
            return response()->json(['message' => ''], 200);
        } else {
            return response()->json(['message' => 'Unable to delete entry'], 500);
        }
    }
}
