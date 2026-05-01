<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    // GET /routes — ambil semua rute
    public function index()
    {
        $routes = Route::with('schedules')->get();
        return response()->json([
            'success' => true,
            'data'    => $routes
        ]);
    }

    // GET /routes/{id} — ambil rute by ID
    public function show($id)
    {
        $route = Route::with('schedules')->find($id);
        if (!$route) {
            return response()->json([
                'success' => false,
                'message' => 'Route not found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data'    => $route
        ]);
    }

    // POST /routes — tambah rute baru
    public function store(Request $request)
    {
        $request->validate([
            'origin'      => 'required|string',
            'destination' => 'required|string',
            'ship_name'   => 'required|string',
            'capacity'    => 'required|integer',
            'price'       => 'required|numeric',
        ]);

        $route = Route::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Route created successfully',
            'data'    => $route
        ], 201);
    }
}
