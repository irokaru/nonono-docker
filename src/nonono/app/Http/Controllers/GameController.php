<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Http\Resources\GameResource;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only(['indexAll']);
    }

    public function index()
    {
        return GameResource::collection(Game::getReleasedGames());
    }

    public function indexAll()
    {
        return GameResource::collection(Game::all());
    }
}
