<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    // GET /tickets — ambil semua tiket (bisa filter by passenger_id)
    public function index(Request $request)
    {
        $query = Ticket::query();

        if ($request->has('passenger_id')) {
            $query->where('passenger_id', $request->passenger_id);
        }

        $tickets = $query->latest()->get();
        return response()->json([
            'success' => true,
            'data'    => $tickets
        ]);
    }

    // GET /tickets/{id} — ambil tiket by ID
    public function show($id)
    {
        $ticket = Ticket::find($id);
        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data'    => $ticket
        ]);
    }

    // GET /tickets/booking/{code} — ambil tiket by booking code
    public function showByCode($code)
    {
        $ticket = Ticket::where('booking_code', $code)->first();
        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data'    => $ticket
        ]);
    }

    // POST /tickets — buat tiket baru (consumer: PassengerService + RouteService)
    public function store(Request $request)
    {
        $request->validate([
            'passenger_id' => 'required|integer',
            'schedule_id'  => 'required|integer',
        ]);

        $passengerServiceUrl = env('PASSENGER_SERVICE_URL', 'http://localhost:8001');
        $routeServiceUrl     = env('ROUTE_SERVICE_URL', 'http://localhost:8002');

        // 1. Ambil data penumpang dari PassengerService
        $passengerResponse = Http::get("{$passengerServiceUrl}/api/passengers/{$request->passenger_id}");
        if (!$passengerResponse->successful() || !$passengerResponse->json('success')) {
            return response()->json([
                'success' => false,
                'message' => 'Passenger not found in PassengerService'
            ], 404);
        }
        $passenger = $passengerResponse->json('data');

        // 2. Ambil data jadwal dari RouteService
        $scheduleResponse = Http::get("{$routeServiceUrl}/api/schedules/{$request->schedule_id}");
        if (!$scheduleResponse->successful() || !$scheduleResponse->json('success')) {
            return response()->json([
                'success' => false,
                'message' => 'Schedule not found in RouteService'
            ], 404);
        }
        $schedule = $scheduleResponse->json('data');

        // 3. Cek ketersediaan kursi
        if ($schedule['available_seats'] <= 0 || $schedule['status'] !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'No available seats or schedule is not active'
            ], 400);
        }

        // 4. Kurangi kursi di RouteService
        $reduceResponse = Http::put("{$routeServiceUrl}/api/schedules/{$request->schedule_id}/reduce-seat");
        if (!$reduceResponse->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reduce seat in RouteService'
            ], 500);
        }

        // 5. Simpan tiket
        $ticket = Ticket::create([
            'booking_code'   => 'FRY-' . strtoupper(Str::random(8)),
            'passenger_id'   => $passenger['id'],
            'schedule_id'    => $schedule['id'],
            'route_id'       => $schedule['route']['id'],
            'passenger_name' => $passenger['name'],
            'origin'         => $schedule['route']['origin'],
            'destination'    => $schedule['route']['destination'],
            'departure_time' => $schedule['departure_time'],
            'total_price'    => $schedule['route']['price'],
            'status'         => 'booked',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ticket booked successfully',
            'data'    => $ticket
        ], 201);
    }

    // PUT /tickets/{id}/cancel — batalkan tiket
    public function cancel($id)
    {
        $ticket = Ticket::find($id);
        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found'
            ], 404);
        }

        if ($ticket->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Ticket already cancelled'
            ], 400);
        }

        $ticket->status = 'cancelled';
        $ticket->save();

        return response()->json([
            'success' => true,
            'message' => 'Ticket cancelled successfully',
            'data'    => $ticket
        ]);
    }
}
