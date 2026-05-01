<?php

namespace App\Http\Controllers;

use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PassengerController extends Controller
{
    // GET /passengers — ambil semua penumpang
    public function index()
    {
        $passengers = Passenger::all();
        return response()->json([
            'success' => true,
            'data' => $passengers
        ]);
    }

    // GET /passengers/{id} — ambil penumpang by ID
    public function show($id)
    {
        $passenger = Passenger::find($id);
        if (!$passenger) {
            return response()->json([
                'success' => false,
                'message' => 'Passenger not found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $passenger
        ]);
    }

    // POST /passengers — tambah penumpang baru
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string',
            'email'     => 'required|email|unique:passengers',
            'phone'     => 'required|string',
            'id_number' => 'required|string|unique:passengers',
        ]);

        $passenger = Passenger::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Passenger created successfully',
            'data'    => $passenger
        ], 201);
    }

    // GET /passengers/{id}/tickets — ambil histori tiket penumpang (consumer)
    public function tickets($id)
    {
        $passenger = Passenger::find($id);
        if (!$passenger) {
            return response()->json([
                'success' => false,
                'message' => 'Passenger not found'
            ], 404);
        }

        // Komunikasi ke TicketService
        $ticketServiceUrl = env('TICKET_SERVICE_URL', 'http://localhost:8003');
        $response = Http::get("{$ticketServiceUrl}/api/tickets", [
            'passenger_id' => $id
        ]);

        return response()->json([
            'success'   => true,
            'passenger' => $passenger,
            'tickets'   => $response->json('data') ?? []
        ]);
    }
}
