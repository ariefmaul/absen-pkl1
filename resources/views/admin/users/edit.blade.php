@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-blue-800 mb-6">Edit User</h1>

        <form method="POST" action="{{ route('admin.users.update', $user->id) }}"
            class="bg-white p-6 rounded-lg shadow-md space-y-6">
            @csrf
            @method('PUT')

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
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 p-2" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sekolah</label>
                <input type="text" name="sekolah" value="{{ old('sekolah', $user->sekolah) }}" required
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 p-2" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">RFID</label>
                <input type="text" name="rfid" value="{{ old('rfid', $user->rfid) }}" required
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 p-2" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-gray-500">(Kosongkan
                        jika tidak ingin mengubah)</span></label>
                <input type="password" name="password"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 p-2" />
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-500">Simpan
                    Perubahan</button>
                <a href="{{ route('admin.users.index') }}"
                    class="bg-gray-400 text-white px-6 py-2 rounded-md hover:bg-gray-500">Batal</a>
            </div>
        </form>
    </div>
@endsection
