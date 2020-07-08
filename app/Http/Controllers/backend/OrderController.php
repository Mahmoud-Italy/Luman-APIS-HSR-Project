<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Order as InboxResource;
use App\Order;

class OrderController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view_orders', ['only' => ['index', 'show']]);
        $this->middleware('permission:delete_orders', ['only' => ['destroy']]);
    }

    public function index()
    {
        $rows = OrderResource::collection(Order::fetchData(request()->all()));
        $data = ['rows' => $rows, 'paginate' => $this->paginate($rows)];
        return response()->json(['data' => $data], 200);
    }

    public function show($id)
    {
        $row = new OrderResource(Order::findOrFail($id));
        return response()->json(['row' => $row], 200);
    }

    public function destroy($id)
    {
        $row = Order::destroy($id);
        if($row) {
            return response()->json(['message' => ''], 200);
        } else {
            return response()->json(['message' => 'Unable to delete entry'], 500);
        }
    }
}
