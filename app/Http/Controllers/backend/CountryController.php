<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Country as CountryResource;
use App\Country;

class CountryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view_countries', ['only' => ['index', 'show']]);
        $this->middleware('permission:add_countries', ['only' => ['store']]);
        $this->middleware('permission:edit_countries', ['only' => ['update']]);
        $this->middleware('permission:delete_countries', ['only' => ['destroy']]);
    }

    public function index()
    {
        $rows = CountryResource::collection(Country::fetchData(request()->all()));
        $data = ['rows' => $rows, 'paginate' => $this->paginate($rows)];
        return response()->json(['data' => $data], 200);
    }

    public function store()
    {
        $row = Country::createOrUpdate(NULL, request()->all(), NULL);
        if($row === true) {
            return response()->json(['message' => ''], 201);
        } else {
            return response()->json(['message' => 'Unable to create entry ' . $row], 500);
        }
    }

    public function show($id)
    {
        $row = new CountryResource(Country::findOrFail($id));
        return response()->json(['row' => $row], 200);
    }

    public function update($id)
    {
        $row = Country::createOrUpdate($id, request()->all(), NULL);
        if($row === true) {
            return response()->json(['message' => ''], 200);
        } else {
            return response()->json(['message' => 'Unable to update entry ' . $row], 500);
        }
    }

    public function destroy($id)
    {
        $row = Country::destroy($id);
        if($row) {
            return response()->json(['message' => ''], 200);
        } else {
            return response()->json(['message' => 'Unable to delete entry'], 500);
        }
    }
}
