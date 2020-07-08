<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Setting as SettingResource;
use App\Setting;

class SettingController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view_settings', ['only' => ['index', 'show']]);
        $this->middleware('permission:edit_settings', ['only' => ['update']]);
    }

    public function index()
    {
        $rows = SettingResource::collection(Setting::fetchData(request()->all()));
        $data = ['rows' => $rows, 'paginate' => $this->paginate($rows)];
        return response()->json(['data' => $data], 200);
    }

    public function update($id)
    {
        $row = Setting::createOrUpdate($id, request()->all(), NULL);
        if($row === true) {
            return response()->json(['message' => ''], 200);
        } else {
            return response()->json(['message' => 'Unable to update entry ' . $row], 500);
        }
    }
}
