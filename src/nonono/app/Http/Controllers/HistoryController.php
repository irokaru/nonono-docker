<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Http\Resources\HistoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HistoryController extends Controller
{
    protected static $_validate = [
        'date' => 'required|date',
        'discription' => 'required|string|min:1|max:200',
    ];

    public function __construct()
    {
        $this->middleware('auth:api')->only(['store', 'update', 'delete']);
    }

    public function index()
    {
        return HistoryResource::collection(History::orderBy('id', 'desc')->get());
    }

    public function store(Request $request)
    {
        $params = $request->only(['date', 'discription']);

        $validator = Validator::make($params, static::$_validate);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        History::create([
            'date'        => $params['date'],
            'discription' => $params['discription'],
        ]);

        return response()->json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        $params = $request->only(['id', 'date', 'discription']);

        $validator = Validator::make($params, static::$_validate);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $history              = History::find($params['id']);
        $history->date        = $params['date'];
        $history->discription = $params['discription'];
        $history->save();

        return response()->json(['status' => 'success'], 200);
    }

    public function delete($id)
    {
        $validator = Validator::make(['id' => $id], ['id' => 'required|integer|min:0']);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $history = History::find($id);
        if (!$history) {
            return response()->json(['status' => 'error', 'message' => 'data not exists'], 400);
        }

        $history->delete();

        return response()->json(['status' => 'success'], 200);
    }
}
