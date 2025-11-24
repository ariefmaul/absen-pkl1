@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold text-blue-800">Daftar User</h2>

        <a href="{{ route('users.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition">
            + Tambah User
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border border-gray-300 rounded-lg">
            <thead class="bg-blue-100 text-blue-900">
                <tr>
                    <th class="p-3 border">#</th>
                    <th class="p-3 border">Nama</th>
                    <th class="p-3 border">Sekolah</th>
                    <th class="p-3 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $i => $u)
                    <tr class="hover:bg-blue-50">
                        <td class="p-3 border text-center">{{ $i + 1 }}</td>
                        <td class="p-3 border">{{ $u->name }}</td>
                        <td class="p-3 border">{{ $u->sekolah }}</td>
                        <td class="p-3 border text-center">
                            <a href="{{ route('users.edit', $u->id) }}"
                                class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 transition">
                                Edit
                            </a>

                            <button onclick="deleteUser({{ $u->id }})"
                                class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">
                                Hapus
                            </button>

                            <form id="delete-form-{{ $u->id }}" action="{{ route('users.delete', $u->id) }}"
                                method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function deleteUser(id) {
            Swal.fire({
                title: "Hapus User?",
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("delete-form-" + id).submit();
                }
            });
        }
    </script>
@endsection
