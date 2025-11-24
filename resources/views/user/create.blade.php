@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-semibold text-blue-800 mb-4">Tambah User</h2>

    <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block font-medium text-blue-900">Nama</label>
            <input type="text" name="name" required class="w-full p-2 border rounded bg-blue-50 border-blue-300">
        </div>

        <div>
            <label class="block font-medium text-blue-900">Sekolah</label>
            <input type="text" name="sekolah" required class="w-full p-2 border rounded bg-blue-50 border-blue-300">
        </div>

        <div>
            <label class="block font-medium text-blue-900">RFID</label>
            <input type="text" name="rfid" required class="w-full p-2 border rounded bg-blue-50 border-blue-300">
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition w-full">
            Simpan
        </button>
    </form>
@endsection
