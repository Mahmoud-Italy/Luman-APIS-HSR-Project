<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Inbox as InboxResource;
use App\Inbox;

class InboxController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view_inbox', ['only' => ['index', 'show']]);
        $this->middleware('permission:delete_inbox', ['only' => ['destroy']]);
    }

    public function index()
    {
        $rows = InboxResource::collection(Inbox::fetchData(request()->all()));
        $data = ['rows' => $rows, 'paginate' => $this->paginate($rows)];
        return response()->json(['data' => $data], 200);
    }

    public function show($id)
    {
        $row = new InboxResource(Inbox::findOrFail($id));
        return response()->json(['row' => $row], 200);
    }

    public function destroy($id)
    {
        $row = Inbox::destroy($id);
        if($row) {
            return response()->json(['message' => ''], 200);
        } else {
            return response()->json(['message' => 'Unable to delete entry'], 500);
        }
    }
}
