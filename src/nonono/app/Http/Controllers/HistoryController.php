<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Http\Resources\HistoryResource;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index()
    {
        return HistoryResource::collection(History::all()->orderBy('id', 'desc'));
    }
}
