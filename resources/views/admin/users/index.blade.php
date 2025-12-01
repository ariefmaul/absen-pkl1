@extends('layouts.app')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-blue-800">Daftar User</h1>
        <button class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-500" onclick="openCreateModal()">
            + Tambah User
        </button>
    </div>

    <!-- Search -->
    <div class="mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-2">
            <input type="text" name="search" placeholder="Cari nama atau RFID..." value="{{ request('search') }}"
                class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 p-2" />
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-500">Cari</button>
            <a href="{{ route('admin.users.index') }}"
                class="bg-gray-400 text-white px-4 py-2 rounded-md hover:bg-gray-500">Reset</a>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full border-collapse bg-white shadow-md rounded-lg">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="border px-6 py-3 text-left">No</th>
                    <th class="border px-6 py-3 text-left">Nama</th>
                    <th class="border px-6 py-3 text-left">Sekolah</th>
                    <th class="border px-6 py-3 text-left">RFID</th>
                    <th class="border px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="userTable">
                @forelse ($users as $user)
                    <tr class="border-b hover:bg-blue-50" id="row-{{ $user->id }}">
                        <td class="border px-6 py-3">
                            {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                        <td class="border px-6 py-3">{{ $user->name }}</td>
                        <td class="border px-6 py-3">{{ $user->sekolah }}</td>
                        <td class="border px-6 py-3 font-mono text-sm">{{ $user->rfid }}</td>
                        <td class="border px-6 py-3 text-center space-x-2">
                            <button class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-sm"
                                onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->sekolah }}', '{{ $user->rfid }}')">
                                Edit
                            </button>
                            <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm"
                                onclick="deleteUser({{ $user->id }})">
                                Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="border px-6 py-3 text-center text-gray-500">Tidak ada data user</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $users->links() }}
    </div>

    <!-- Modal Create/Edit User -->
    <div id="userModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal()"></div>

            <!-- Modal box -->
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">Tambah User</h3>

                    <form id="userForm" class="mt-4 space-y-4">
                        @csrf
                        <input type="hidden" id="userId" name="id">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                            <input type="text" id="name" name="name" required
                                class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 p-2" />
                            <span class="text-red-500 text-sm" id="nameError"></span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sekolah</label>
                            <input type="text" id="sekolah" name="sekolah" required
                                class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 p-2" />
                            <span class="text-red-500 text-sm" id="sekolahError"></span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">RFID</label>
                            <input type="text" id="rfid" name="rfid" required
                                class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 p-2"
                                placeholder="Scan RFID atau input manual" />
                            <span class="text-red-500 text-sm" id="rfidError"></span>
                        </div>
                    </form>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="saveUser()"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan
                    </button>
                    <button type="button" onclick="closeModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        const userModal = document.getElementById('userModal');
        let isEditMode = false;

        function openCreateModal() {
            isEditMode = false;
            document.getElementById('modalTitle').innerText = 'Tambah User';
            document.getElementById('userForm').reset();
            document.getElementById('userId').value = '';
            clearErrors();
            userModal.classList.remove('hidden');
        }

        function openEditModal(id, name, sekolah, rfid) {
            isEditMode = true;
            document.getElementById('modalTitle').innerText = 'Edit User';
            document.getElementById('userId').value = id;
            document.getElementById('name').value = name;
            document.getElementById('sekolah').value = sekolah;
            document.getElementById('rfid').value = rfid;
            clearErrors();
            userModal.classList.remove('hidden');
        }

        function closeModal() {
            userModal.classList.add('hidden');
            clearErrors();
        }

        function clearErrors() {
            document.getElementById('nameError').innerText = '';
            document.getElementById('sekolahError').innerText = '';
            document.getElementById('rfidError').innerText = '';
        }

        function saveUser() {
            clearErrors();
            const id = document.getElementById('userId').value;
            const name = document.getElementById('name').value;
            const sekolah = document.getElementById('sekolah').value;
            const rfid = document.getElementById('rfid').value;

            const data = {
                _token: document.querySelector('input[name="_token"]').value,
                name: name,
                sekolah: sekolah,
                rfid: rfid
            };

            const url = id ? `/admin/users/${id}` : '/admin/users';
            const method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: data,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: isEditMode ? 'User berhasil diperbarui' : 'User berhasil ditambahkan',
                        confirmButtonColor: '#3b82f6'
                    }).then(() => {
                        location.reload();
                    });
                    closeModal();
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        if (errors.name) document.getElementById('nameError').innerText = errors.name[0];
                        if (errors.sekolah) document.getElementById('sekolahError').innerText = errors.sekolah[
                            0];
                        if (errors.rfid) document.getElementById('rfidError').innerText = errors.rfid[0];
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menyimpan data',
                            confirmButtonColor: '#ef4444'
                        });
                    }
                }
            });
        }

        function deleteUser(id) {
            Swal.fire({
                title: 'Hapus User?',
                text: 'Data user akan dihapus secara permanen',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/users/${id}`,
                        method: 'DELETE',
                        data: {
                            _token: document.querySelector('input[name="_token"]').value
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'User berhasil dihapus',
                                confirmButtonColor: '#3b82f6'
                            }).then(() => {
                                $(`#row-${id}`).fadeOut(300, function() {
                                    $(this).remove();
                                });
                            });
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menghapus user',
                                confirmButtonColor: '#ef4444'
                            });
                        }
                    });
                }
            });
        }

        // Close modal when clicking outside
        userModal.addEventListener('click', function(event) {
            if (event.target === userModal) {
                closeModal();
            }
        });
    </script>
@endsection
