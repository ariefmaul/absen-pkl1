@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-blue-800">Riwayat Absensi</h1>
    </div>

    <!-- Search & Filter -->
    <div class="mb-6 bg-white p-4 rounded-lg shadow-md space-y-4">
        <form method="GET" action="{{ route('admin.attendance.history') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Nama User</label>
                    <input type="text" name="search" placeholder="Nama user..." value="{{ request('search') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 p-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter Bulan</label>
                    <input type="month" name="month" value="{{ request('month') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 p-2" />
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-500 flex-1">Filter</button>
                    <a href="{{ route('admin.attendance.history') }}"
                        class="bg-gray-400 text-white px-4 py-2 rounded-md hover:bg-gray-500">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full border-collapse bg-white shadow-md rounded-lg">
            <thead class="bg-green-600 text-white">
                <tr>
                    <th class="border px-6 py-3 text-left">No</th>
                    <th class="border px-6 py-3 text-left">Nama User</th>
                    <th class="border px-6 py-3 text-left">Tanggal</th>
                    <th class="border px-6 py-3 text-center">Masuk</th>
                    <th class="border px-6 py-3 text-center">Keluar</th>
                    <th class="border px-6 py-3 text-left">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($attendances as $attendance)
                    <tr class="border-b hover:bg-green-50">
                        <td class="border px-6 py-3">
                            {{ ($attendances->currentPage() - 1) * $attendances->perPage() + $loop->iteration }}</td>
                        <td class="border px-6 py-3 font-medium">{{ $attendance->user->name }}</td>
                        <td class="border px-6 py-3">{{ $attendance->scanned_at->format('d-m-Y') }}</td>
                        <td class="border px-6 py-3 text-center">
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded text-sm font-medium">
                                {{ $attendance->check_in ?? '-' }}
                            </span>
                        </td>
                        <td class="border px-6 py-3 text-center">
                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded text-sm font-medium">
                                {{ $attendance->check_out ?? '-' }}
                            </span>
                        </td>
                        <td class="border px-6 py-3 text-sm text-gray-600">{{ $attendance->note ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="border px-6 py-3 text-center text-gray-500">Tidak ada data absensi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $attendances->links() }}
    </div>
@endsection
