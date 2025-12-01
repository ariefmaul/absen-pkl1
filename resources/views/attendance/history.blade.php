@extends('layouts.utama')

@section('content')
    <div class="max-w-6xl mx-auto py-6">

        <!-- FILTER CARD -->
        <div class="bg-white p-5 rounded-xl shadow-md mb-5 border-t-4 ">
            <form method="GET" action="" class="grid grid-cols-1 md:grid-cols-4 gap-4">

                {{-- Search --}}
                <div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full px-4 py-2 rounded-lg border border-blue-200 bg-blue-50 focus:ring-2 focus:ring-blue-400"
                        placeholder="Cari nama atau catatan...">
                </div>

                {{-- Filter Bulan --}}
                <div>
                    <input type="month" name="month" value="{{ request('month') }}"
                        class="w-full px-4 py-2 rounded-lg border border-blue-200 bg-blue-50 focus:ring-2 focus:ring-blue-400">
                </div>


                {{-- Per Page --}}
                <div>
                    <select name="per_page"
                        class="w-full px-4 py-2 rounded-lg border border-blue-200 bg-blue-50 focus:ring-2 focus:ring-blue-400">
                        <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5 per halaman</option>
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 per halaman</option>
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 per halaman</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per halaman</option>
                    </select>
                </div>

                {{-- Button --}}
                <div>
                    <button
                        class="w-full bg-blue-500 hover:bg-blue-600 transition text-white font-medium py-2 rounded-lg shadow">
                        Filter
                    </button>
                </div>

            </form>

        </div>

        <!-- TABLE CARD -->
        <div class="bg-white rounded-xl shadow-md border-t-4 border-blue-400">
            <div class="px-5 py-3 bg-blue-400 rounded-t-xl">
                <h5 class="text-white text-lg font-semibold">Data Absen Hari Ini</h5>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-blue-50 border-b">
                            <th class="px-4 py-3 font-semibold">#</th>
                            <th class="px-4 py-3 font-semibold">User</th>
                            <th class="px-4 py-3 font-semibold">Sekolah</th>
                            <th class="px-4 py-3 font-semibold">Jam Masuk</th>
                            <th class="px-4 py-3 font-semibold">Jam Keluar</th>
                            <th class="px-4 py-3 font-semibold">Jumlah</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($attendances as $a)
                            <tr class="border-b hover:bg-blue-50 transition">
                                <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3">{{ $a->user->name ?? 'Unknown' }}</td>
                                <td class="px-4 py-3">{{ $a->user->sekolah ?? 'Unknown' }}</td>

                                <td class="px-4 py-3">
                                    @if ($a->check_in)
                                        <span class="px-3 py-1 rounded-full text-sm bg-green-100 text-green-600">
                                            {{ $a->check_in }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    @if ($a->check_out)
                                        <span class="px-3 py-1 rounded-full text-sm bg-red-100 text-red-600">
                                            {{ $a->check_out }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    @if ($a->jumlah_jam)
                                        {{ number_format($a->jumlah_jam, 2) }} jam
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-gray-500">
                                    Tidak ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

                <div class="mt-4">
                    {{ $attendances->links() }}
                </div>

            </div>
        </div>

        <div class="mt-5">
            {{ $attendances->appends(request()->query())->links() }}
        </div>

    </div>
@endsection
