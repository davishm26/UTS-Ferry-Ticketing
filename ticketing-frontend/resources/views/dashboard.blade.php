<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferry Ticketing Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-8 text-blue-800">🚢 Ferry Ticketing System</h1>

        @if(session('success'))
            <div class="bg-green-200 text-green-800 p-4 mb-4 rounded">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-200 text-red-800 p-4 mb-4 rounded">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <!-- FORM BELI TIKET -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4 border-b pb-2">Beli Tiket Baru</h2>
                <form action="{{ route('buy.ticket') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block mb-1">Pilih Penumpang:</label>
                        <select name="passenger_id" class="w-full border p-2 rounded">
                            @foreach($passengers as $p)
                                <option value="{{ $p['id'] }}">{{ $p['name'] }} ({{ $p['id_number'] }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-1">Pilih Jadwal Kapal:</label>
                        <select name="schedule_id" class="w-full border p-2 rounded">
                            @foreach($routes as $route)
                                @foreach($route['schedules'] as $s)
                                    <option value="{{ $s['id'] }}">
                                        {{ $route['ship_name'] }} | {{ $route['origin'] }} -> {{ $route['destination'] }} |
                                        {{ $s['departure_time'] }} (Sisa: {{ $s['available_seats'] }})
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Pesan
                        Sekarang</button>
                </form>
            </div>

            <!-- DAFTAR TRANSAKSI -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4 border-b pb-2">Transaksi Terakhir</h2>
                <div class="overflow-y-auto h-64">
                    @foreach($tickets as $t)
                        <div class="mb-3 p-3 bg-gray-50 border-l-4 border-blue-500 rounded">
                            <p class="font-bold">{{ $t['booking_code'] }} - {{ $t['passenger_name'] }}</p>
                            <p class="text-sm text-gray-600">{{ $t['origin'] }} ➔ {{ $t['destination'] }}</p>
                            <p class="text-xs text-gray-400">{{ $t['created_at'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</body>

</html>