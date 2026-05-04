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

    // Tambahkan di dalam class DashboardController

    // Fungsi untuk Update (Contoh: Selesaikan Tiket)
    public function updateTicket($id)
    {
        // Mengirim request ke TicketService
        $response = Http::put(env('TICKET_SERVICE_URL') . "/tickets/{$id}/cancel");

        if ($response->successful()) {
            return back()->with('success', 'Status tiket berhasil diperbarui.');
        }

        return back()->with('error', 'Gagal memperbarui tiket.');
    }

    // Fungsi untuk Delete
    public function deleteTicket($id)
    {
        // Catatan: Pastikan di TicketService backend kamu sudah ada route DELETE
        $response = Http::delete(env('TICKET_SERVICE_URL') . "/tickets/{$id}");

        if ($response->successful()) {
            return back()->with('success', 'Tiket berhasil dihapus.');
        }

        return back()->with('error', 'Gagal menghapus tiket.');
    }
}