<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Page as CountryResource;
use App\Page;

class PageController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view_pages', ['only' => ['index', 'show']]);
        $this->middleware('permission:add_pages', ['only' => ['store']]);
        $this->middleware('permission:edit_pages', ['only' => ['update']]);
        $this->middleware('permission:delete_pages', ['only' => ['destroy']]);
    }

    public function index()
    {
        $rows = PageResource::collection(Page::fetchData(request()->all()));
        $data = ['rows' => $rows, 'paginate' => $this->paginate($rows)];
        return response()->json(['data' => $data], 200);
    }

    public function store()
    {
        $row = Page::createOrUpdate(NULL, request()->all(), NULL);
        if($row === true) {
            return response()->json(['message' => ''], 201);
        } else {
            return response()->json(['message' => 'Unable to create entry ' . $row], 500);
        }
    }

    public function show($id)
    {
        $row = new PageResource(Page::findOrFail($id));
        return response()->json(['row' => $row], 200);
    }

    public function update($id)
    {
        $row = Page::createOrUpdate($id, request()->all(), NULL);
        if($row === true) {
            return response()->json(['message' => ''], 200);
        } else {
            return response()->json(['message' => 'Unable to update entry ' . $row], 500);
        }
    }

    public function destroy($id)
    {
        $row = Page::destroy($id);
        if($row) {
            return response()->json(['message' => ''], 200);
        } else {
            return response()->json(['message' => 'Unable to delete entry'], 500);
        }
    }
}
