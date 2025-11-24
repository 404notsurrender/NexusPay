<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BannerController extends Controller
{
	/**
	 * Display a simple response to verify the controller is working.
	 */
	public function index(Request $request)
	{
		return response()->json(['status' => 'success', 'message' => 'BannerController is working']);
	}
}
