<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Route;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    // GET /schedules — ambil semua jadwal
    public function index()
    {
        $schedules = Schedule::with('route')->get();
        return response()->json([
            'success' => true,
            'data'    => $schedules
        ]);
    }

    // GET /schedules/{id} — ambil jadwal by ID
    public function show($id)
    {
        $schedule = Schedule::with('route')->find($id);
        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Schedule not found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data'    => $schedule
        ]);
    }

    // POST /schedules — tambah jadwal baru
    public function store(Request $request)
    {
        $request->validate([
            'route_id'        => 'required|exists:routes,id',
            'departure_time'  => 'required|date',
            'arrival_time'    => 'required|date|after:departure_time',
            'available_seats' => 'required|integer',
        ]);

        $schedule = Schedule::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Schedule created successfully',
            'data'    => $schedule->load('route')
        ], 201);
    }

    // PUT /schedules/{id}/reduce-seat — kurangi kursi saat tiket dibeli
    public function reduceSeat($id)
    {
        $schedule = Schedule::find($id);
        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Schedule not found'
            ], 404);
        }

        if ($schedule->available_seats <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'No available seats'
            ], 400);
        }

        $schedule->available_seats -= 1;
        if ($schedule->available_seats == 0) {
            $schedule->status = 'full';
        }
        $schedule->save();

        return response()->json([
            'success' => true,
            'message' => 'Seat reduced successfully',
            'data'    => $schedule
        ]);
    }
}
