<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Report as ReportResource;
use App\Report;

class ReportController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view_reports', ['only' => ['index', 'show']]);
        $this->middleware('permission:add_reports', ['only' => ['store']]);
        $this->middleware('permission:edit_reports', ['only' => ['update']]);
        $this->middleware('permission:delete_reports', ['only' => ['destroy']]);
    }

    public function index()
    {
        $rows = ReportResource::collection(Report::fetchData(request()->all()));
        $data = ['rows' => $rows, 'paginate' => $this->paginate($rows)];
        return response()->json(['data' => $data], 200);
    }

    public function store()
    {
        $row = Report::createOrUpdate(NULL, request()->all());
        if($row === true) {
            return response()->json(['message' => ''], 201);
        } else {
            return response()->json(['message' => 'Unable to create entry ' . $row], 500);
        }
    }

    public function show($id)
    {
        $row = new ReportResource(Report::findOrFail($id));
        return response()->json(['row' => $row], 200);
    }

    public function update($id)
    {
        $row = Report::createOrUpdate($id, request()->all());
        if($row === true) {
            return response()->json(['message' => ''], 200);
        } else {
            return response()->json(['message' => 'Unable to update entry ' . $row], 500);
        }
    }

    public function destroy($id)
    {
        $row = Report::destroy($id);
        if($row) {
            return response()->json(['message' => ''], 200);
        } else {
            return response()->json(['message' => 'Unable to delete entry'], 500);
        }
    }
}
