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
                <div class="overflow-y-auto h-96">
                    @foreach($tickets as $t)
                        <div
                            class="mb-4 p-4 bg-gray-50 border-l-4 {{ $t['status'] == 'cancelled' ? 'border-red-500' : 'border-blue-500' }} rounded shadow-sm">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-bold text-lg">{{ $t['booking_code'] }}</p>
                                    <p class="text-sm font-medium text-gray-700">{{ $t['passenger_name'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $t['origin'] }} ➔ {{ $t['destination'] }}</p>
                                    <p class="text-xs mt-1">Status:
                                        <span
                                            class="px-2 py-0.5 rounded text-white text-[10px] {{ $t['status'] == 'cancelled' ? 'bg-red-500' : 'bg-green-500' }}">
                                            {{ strtoupper($t['status']) }}
                                        </span>
                                    </p>
                                </div>

                                <!-- TOMBOL AKSI -->
                                <div class="flex flex-col space-y-2">
                                    @if($t['status'] !== 'cancelled')
                                        <form action="{{ route('tickets.update', $t['id']) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                class="text-xs bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 w-full text-center">
                                                Cancel
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('tickets.destroy', $t['id']) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-xs bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 w-full text-center">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</body>

</html>