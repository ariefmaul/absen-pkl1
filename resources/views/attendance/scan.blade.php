@extends('layouts.utama')

@section('content')
    <div class="max-w-xl mx-auto py-8">

        <div class="text-center mb-6">
            <h2 class="text-3xl font-semibold text-blue-900">Scan RFID</h2>
            <p class="text-gray-500">Tempelkan kartu RFID Anda pada scanner</p>
        </div>

        <!-- CARD -->
        <div class="bg-white shadow-lg rounded-xl p-6 border-t-4 border-blue-400">

            <form id="scan-form" class="space-y-4">

                <label for="rfid" class="text-blue-900 font-semibold">
                    Tempelkan Kartu RFID Anda
                </label>

                <input type="text" id="rfid" name="rfid"
                    class="w-full px-4 py-3 rounded-lg text-lg border border-blue-200 bg-blue-50 focus:ring-2 focus:ring-blue-400 outline-none"
                    placeholder="Tempel kartu RFID..." autocomplete="off" autofocus required>

                <button type="submit" id="scan-btn"
                    class="w-full py-3 rounded-lg font-semibold text-white bg-blue-500 hover:bg-blue-600 transition-all shadow-md">
                    Silahkan Tempelkan Kartu RFID di Scanner
                </button>

            </form>

            <div id="result" class="mt-4"></div>
        </div>

    </div>

    <script>
        document.getElementById('scan-form').addEventListener('submit', function(e) {
            e.preventDefault();

            let rfid = document.getElementById('rfid').value;
            let btn = document.getElementById('scan-btn');

            btn.disabled = true;
            btn.innerHTML = `Memproses...`;

            fetch("{{ route('attendance.scan.post') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        rfid
                    })
                })
                .then(async response => {
                    const data = await response.json();

                    btn.disabled = false;
                    btn.innerHTML = `Scan Sekarang`;

                    // Jika HTTP bukan 200
                    if (!response.ok) {
                        Swal.fire({
                            icon: "error",
                            title: "Scan Gagal!",
                            text: data.message ?? "RFID tidak dikenali.",
                            // confirmButtonColor: "#d33"
                            timer: 2000, // otomatis menutup setelah 2 detik
                            timerProgressBar: true, // menampilkan progress bar
                            showConfirmButton: false, // sembunyikan tombol OK
                        });

                        document.getElementById('rfid').value = "";
                        document.getElementById('rfid').focus();
                        return;
                    }

                    // Jika sukses
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil Scan!",
                        html: `
        <b class='text-blue-900'>${data.data.user.name}</b><br>
        ${data.data.type}<br>
        <small>${data.data.scanned_at}</small>
    `,
                        timer: 2000, // otomatis menutup setelah 2 detik
                        timerProgressBar: true, // menampilkan progress bar
                        showConfirmButton: false, // sembunyikan tombol OK
                    });


                    document.getElementById('rfid').value = "";
                    document.getElementById('rfid').focus();
                })
                .catch(() => {
                    btn.disabled = false;
                    btn.innerHTML = `Scan Sekarang`;

                    Swal.fire({
                        icon: "error",
                        title: "Koneksi Error",
                        text: "Tidak dapat terhubung ke server.",
                        // confirmButtonColor: "#d33"
                        timer: 2000, // otomatis menutup setelah 2 detik
                        timerProgressBar: true, // menampilkan progress bar
                        showConfirmButton: false, // sembunyikan tombol OK
                    });
                    document.getElementById('rfid').focus();

                });
        });
    </script>
@endsection
