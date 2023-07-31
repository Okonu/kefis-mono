<?php

namespace App\Http\Controllers;

use App\Models\FulfilledOrder;

class ReorderController extends Controller
{
    public function index()
    {
        $reorders = FulfilledOrder::all();

        return response()->json($reorders);
    }

    public function show(FulfilledOrder $reorder)
    {
        return response()->json($reorder);
    }

    public function dispatch(FulfilledOrder $reorder)
    {
        $reorder->status = 'Fulfilled';
        $reorder->save();

        return response()->json(['message' => 'Reorder dispatched successfully']);
    }
}
