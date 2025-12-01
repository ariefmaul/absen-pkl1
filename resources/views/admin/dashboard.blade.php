@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-blue-800">Dashboard Admin</h1>
        <p class="text-gray-600">Kelola aplikasi absensi dari sini</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Card Total Users -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 mb-1">Total User</p>
                    <h2 class="text-4xl font-bold">{{ $totalUsers }}</h2>
                </div>
                <div class="text-5xl opacity-30">👥</div>
            </div>
        </div>

        <!-- Card Today Attendance -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 mb-1">Absen Hari Ini</p>
                    <h2 class="text-4xl font-bold">{{ $todayAttendance }}</h2>
                </div>
                <div class="text-5xl opacity-30">✓</div>
            </div>
        </div>

        <!-- Card Total Attendance -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-6 rounded-lg shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 mb-1">Total Absen</p>
                    <h2 class="text-4xl font-bold">{{ $totalAttendance }}</h2>
                </div>
                <div class="text-5xl opacity-30">📊</div>
            </div>
        </div>
    </div>

    <!-- Quick Attendance Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <button class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 font-semibold text-lg transition"
            onclick="openCheckInModal()">
            ✓ Absen Masuk
        </button>
        <button class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 font-semibold text-lg transition"
            onclick="openCheckOutModal()">
            ✕ Absen Keluar
        </button>
    </div>

    <!-- Menu Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
            <h3 class="text-xl font-semibold text-blue-800 mb-4">Manajemen User</h3>
            <div class="space-y-2">
                <a href="{{ route('admin.users.index') }}" class="block text-blue-600 hover:underline">→ Lihat Daftar
                    User</a>
                <a href="{{ route('admin.users.create') }}" class="block text-blue-600 hover:underline">→ Tambah User
                    Baru</a>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
            <h3 class="text-xl font-semibold text-green-800 mb-4">Manajemen Absensi</h3>
            <div class="space-y-2">
                <a href="{{ route('admin.attendance.history') }}" class="block text-green-600 hover:underline">→ Lihat
                    Riwayat Absen</a>
                <a href="{{ route('admin.attendance.manual') }}" class="block text-green-600 hover:underline">→ Input Absen
                    Manual</a>
            </div>
        </div>
    </div>

    <!-- Attendance Modal -->
    <div id="attendanceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center"
        style="display: none;">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-auto p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4" id="modalTitle">Absen Masuk</h2>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Pilih User</label>
                <select id="userSelect"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                    required>
                    <option value="">-- Pilih User --</option>
                </select>
                <span id="userError" class="text-red-600 text-sm hidden mt-1"></span>
            </div>

            <div class="flex gap-3">
                <button onclick="closeAttendanceModal()"
                    class="flex-1 bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500 transition">
                    Batal
                </button>
                <button id="submitBtn" onclick="submitAttendance()"
                    class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Simpan
                </button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let attendanceMode = 'check-in'; // 'check-in' or 'check-out'

        // Fetch and populate user list
        function populateUserSelect() {
            $.ajax({
                url: '{{ route('admin.users.index') }}',
                type: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                dataType: 'json',
                success: function(users) {
                    const userSelect = $('#userSelect');
                    userSelect.empty();
                    userSelect.append('<option value="">-- Pilih User --</option>');

                    users.forEach(user => {
                        userSelect.append(
                            `<option value="${user.id}">${user.name} (${user.sekolah})</option>`);
                    });
                },
                error: function() {
                    Swal.fire('Error', 'Gagal memuat daftar user', 'error');
                }
            });
        }

        function openCheckInModal() {
            attendanceMode = 'check-in';
            $('#modalTitle').text('Absen Masuk');
            $('#submitBtn').text('Simpan').removeClass('bg-red-600 hover:bg-red-700').addClass(
                'bg-blue-600 hover:bg-blue-700');
            $('#userSelect').val('');
            $('#userError').addClass('hidden');
            populateUserSelect();
            $('#attendanceModal').css('display', 'flex');
        }

        function openCheckOutModal() {
            attendanceMode = 'check-out';
            $('#modalTitle').text('Absen Keluar');
            $('#submitBtn').text('Simpan').removeClass('bg-blue-600 hover:bg-blue-700').addClass(
                'bg-red-600 hover:bg-red-700');
            $('#userSelect').val('');
            $('#userError').addClass('hidden');
            populateUserSelect();
            $('#attendanceModal').css('display', 'flex');
        }

        function closeAttendanceModal() {
            $('#attendanceModal').css('display', 'none');
            $('#userSelect').val('');
            $('#userError').addClass('hidden');
        }

        function submitAttendance() {
            const userId = $('#userSelect').val();

            if (!userId) {
                $('#userError').text('Silakan pilih user').removeClass('hidden');
                return;
            }

            const route = attendanceMode === 'check-in' ?
                '{{ route('admin.attendance.checkIn') }}' :
                '{{ route('admin.attendance.checkOut') }}';

            $.ajax({
                url: route,
                type: 'POST',
                data: {
                    user_id: userId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    closeAttendanceModal();

                    const message = attendanceMode === 'check-in' ?
                        'Absen Masuk berhasil disimpan' :
                        'Absen Keluar berhasil disimpan';

                    Swal.fire('Sukses', message, 'success').then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    let errorMessage = 'Terjadi kesalahan';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    $('#userError').text(errorMessage).removeClass('hidden');
                }
            });
        }

        // Close modal when clicking outside
        $('#attendanceModal').click(function(e) {
            if (e.target.id === 'attendanceModal') {
                closeAttendanceModal();
            }
        });
    </script>
@endsection
