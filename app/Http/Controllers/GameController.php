<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;

class GameController extends Controller
{
    public function index()
    {
        return response()->json(Game::where('status', 'active')->get());
    }

    public function show(Game $game)
    {
        return response()->json($game);
    }
}
