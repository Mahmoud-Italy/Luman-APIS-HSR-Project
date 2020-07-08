<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Slider as SliderResource;
use App\Slider;

class SliderController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view_sliders', ['only' => ['index', 'show']]);
        $this->middleware('permission:add_sliders', ['only' => ['store']]);
        $this->middleware('permission:edit_sliders', ['only' => ['update']]);
        $this->middleware('permission:delete_sliders', ['only' => ['destroy']]);
    }

    public function index()
    {
        $rows = SliderResource::collection(Slider::fetchData(request()->all()));
        $data = ['rows' => $rows, 'paginate' => $this->paginate($rows)];
        return response()->json(['data' => $data], 200);
    }

    public function store()
    {
        $row = Slider::createOrUpdate(NULL, request()->all(), NULL);
        if($row === true) {
            return response()->json(['message' => ''], 201);
        } else {
            return response()->json(['message' => 'Unable to create entry ' . $row], 500);
        }
    }

    public function show($id)
    {
        $row = new SliderResource(Slider::findOrFail($id));
        return response()->json(['row' => $row], 200);
    }

    public function update($id)
    {
        $row = Slider::createOrUpdate($id, request()->all(), NULL);
        if($row === true) {
            return response()->json(['message' => ''], 200);
        } else {
            return response()->json(['message' => 'Unable to update entry ' . $row], 500);
        }
    }

    public function destroy($id)
    {
        $row = Slider::destroy($id);
        if($row) {
            return response()->json(['message' => ''], 200);
        } else {
            return response()->json(['message' => 'Unable to delete entry'], 500);
        }
    }
}
