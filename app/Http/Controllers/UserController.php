<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
	/**
	 * Display a simple JSON response to verify the controller is working.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index()
	{
		return response()->json(['message' => 'UserController is working']);
	}

	/**
	 * Example store method accepting a Request to avoid unused import errors.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store(Request $request)
	{
		// Validate example (adjust rules as needed)
		$validated = $request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|email',
		]);

		// Return the validated data for now
		return response()->json(['data' => $validated], 201);
	}
}
