@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-semibold text-blue-800 mb-4">Edit User</h2>

    <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block font-medium text-blue-900">Nama</label>
            <input type="text" name="name" value="{{ $user->name }}" required
                class="w-full p-2 border rounded bg-blue-50 border-blue-300">
        </div>

        <div>
            <label class="block font-medium text-blue-900">Sekolah</label>
            <input type="text" name="sekolah" value="{{ $user->sekolah }}" required
                class="w-full p-2 border rounded bg-blue-50 border-blue-300">
        </div>

        <div>
            <label class="block font-medium text-blue-900">RFID</label>
            <input type="text" value="{{ $user->rfid }}" required name="rfid"
                class="w-full p-2 border rounded bg-blue-50 border-blue-300" placeholder="RFID">
        </div>

        <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition w-full">
            Update
        </button>
    </form>
@endsection
