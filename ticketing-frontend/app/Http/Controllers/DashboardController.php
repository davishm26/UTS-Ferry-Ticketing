<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data dari masing-masing service
        $passengers = Http::get(env('PASSENGER_SERVICE_URL') . '/passengers')->json('data') ?? [];
        $routes = Http::get(env('ROUTE_SERVICE_URL') . '/routes')->json('data') ?? [];
        $tickets = Http::get(env('TICKET_SERVICE_URL') . '/tickets')->json('data') ?? [];

        return view('dashboard', compact('passengers', 'routes', 'tickets'));
    }

    public function buyTicket(Request $request)
    {
        $response = Http::post(env('TICKET_SERVICE_URL') . '/tickets', [
            'passenger_id' => $request->passenger_id,
            'schedule_id' => $request->schedule_id,
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Tiket berhasil dibeli! Kode: ' . $response->json('data.booking_code'));
        }

        return back()->with('error', 'Gagal membeli tiket: ' . $response->json('message'));
    }
}