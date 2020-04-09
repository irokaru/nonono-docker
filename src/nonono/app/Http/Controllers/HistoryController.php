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
        $this->middleware('auth:api')->only('store');
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
}
