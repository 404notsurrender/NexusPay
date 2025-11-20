<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Game;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\Deposit;
use App\Models\PaymentMethod;
use App\Models\Banner;
use App\Models\Popup;
use App\Models\Config;
use App\Models\PopularGame;
use App\Models\AdminIp;

class AdminController extends Controller
{
    // User management
    public function getUsers()
    {
        return response()->json(User::all());
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,member',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json($user, 201);
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,member',
        ]);

        $user->update($request->only(['name', 'email', 'role']));

        return response()->json($user);
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }

    public function resetUserPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|min:8',
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        return response()->json(['message' => 'Password reset']);
    }

    // Game management
    public function getGames()
    {
        return response()->json(Game::all());
    }

    public function createGame(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'nullable|image',
            'status' => 'required|in:active,inactive',
        ]);

        $game = Game::create($request->all());

        return response()->json($game, 201);
    }

    public function updateGame(Request $request, Game $game)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'nullable|image',
            'status' => 'required|in:active,inactive',
        ]);

        $game->update($request->all());

        return response()->json($game);
    }

    public function deleteGame(Game $game)
    {
        $game->delete();
        return response()->json(['message' => 'Game deleted']);
    }

    // Similar methods for Category, Product, Order, Deposit, PaymentMethod, Banner, Popup, Config, PopularGame

    // Config management
    public function getConfigs()
    {
        return response()->json(Config::all());
    }

    public function updateConfig(Request $request, Config $config)
    {
        $request->validate([
            'value' => 'required',
        ]);

        $config->update(['value' => $request->value]);

        return response()->json($config);
    }

    // IP Whitelist
    public function getAdminIps()
    {
        return response()->json(AdminIp::with('user')->get());
    }

    public function addAdminIp(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'ip' => 'required|ip',
        ]);

        $ip = AdminIp::create($request->all());

        return response()->json($ip, 201);
    }

    public function removeAdminIp(AdminIp $ip)
    {
        $ip->delete();
        return response()->json(['message' => 'IP removed']);
    }

    // Dashboard analytics
    public function getAnalytics()
    {
        $totalTransactions = Order::count();
        $totalMemberBalance = User::where('role', 'member')->sum('balance'); // Assuming balance field
        $dailyTransactions = Order::whereDate('created_at', today())->count();

        return response()->json([
            'total_transactions' => $totalTransactions,
            'total_member_balance' => $totalMemberBalance,
            'daily_transactions' => $dailyTransactions,
        ]);
    }
}
