@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-blue-800 mb-6">Input Absensi Manual</h1>

        <form method="POST" action="{{ route('admin.attendance.manual.store') }}"
            class="bg-white p-6 rounded-lg shadow-md space-y-6">
            @csrf

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                <select name="user_id" required
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 p-2">
                    <option value="">-- Pilih User --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->sekolah }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Absensi</label>
                <input type="date" name="scanned_at" value="{{ old('scanned_at') }}" required
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 p-2" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jam Masuk</label>
                    <input type="time" name="check_in" value="{{ old('check_in') }}" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 p-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jam Keluar (Opsional)</label>
                    <input type="time" name="check_out" value="{{ old('check_out') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 p-2" />
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                <textarea name="note" rows="3"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 p-2"
                    placeholder="Contoh: Sakit, Izin, dsb..."></textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-500">Simpan
                    Absensi</button>
                <a href="{{ route('admin.attendance.history') }}"
                    class="bg-gray-400 text-white px-6 py-2 rounded-md hover:bg-gray-500">Batal</a>
            </div>
        </form>
    </div>
@endsection
