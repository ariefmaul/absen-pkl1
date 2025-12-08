@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto py-8">

        <h2 class="text-3xl font-semibold text-blue-900 mb-6">Pengaturan Absen</h2>

        <div class="bg-white shadow-lg rounded-xl p-6 border-t-4 border-blue-400">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('attendance.settings.update') }}" class="space-y-5">
                @csrf

                <!-- Check In -->
                <div>
                    <label for="check_in_time" class="block mb-1 text-blue-900 font-semibold">
                        Absen Masukk (HH:MM)
                    </label>
                    <input type="time" id="check_in_time" name="check_in_time"
                        class="w-full px-4 py-3 rounded-lg border border-blue-200 bg-blue-50 focus:ring-2 focus:ring-blue-400 outline-none"
                        value="{{ optional($setting)->check_in_time }}">
                </div>

                <!-- Check Out -->
                <div>
                    <label for="check_out_time" class="block mb-1 text-blue-900 font-semibold">
                        Absen Pulang (HH:MM)
                    </label>
                    <input type="time" id="check_out_time" name="check_out_time"
                        class="w-full px-4 py-3 rounded-lg border border-blue-200 bg-blue-50 focus:ring-2 focus:ring-blue-400 outline-none"
                        value="{{ optional($setting)->check_out_time }}">
                </div>

                <button type="submit"
                    class="w-full py-3 font-semibold text-white bg-blue-500 hover:bg-blue-600 rounded-lg transition shadow-md">
                    Simpan
                </button>

            </form>
        </div>

    </div>
@endsection
