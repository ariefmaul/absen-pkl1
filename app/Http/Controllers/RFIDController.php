<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RFIDController extends Controller
{
    public function scanView()
    {
        $setting = AttendanceSetting::first();
        return view('attendance.scan', compact('setting'));
    }

    public function scan(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $request->validate([
            'rfid' => 'required|string'
        ]);

        $rfid = $request->rfid;
        $now  = Carbon::now();

        // Ambil user berdasarkan RFID
        $user = User::where('rfid', $rfid)->first();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'RFID tidak terdaftar'
            ], 404);
        }

        // Ambil pengaturan absensi
        $setting = AttendanceSetting::first();
        $checkInTime  = Carbon::createFromTimeString($setting->check_in_time);
        $checkOutTime = Carbon::createFromTimeString($setting->check_out_time);

        // Cek absensi terakhir hari ini
        $last = Attendance::where('user_id', $user->id)
            ->whereDate('scanned_at', $now->toDateString())
            ->latest('scanned_at')
            ->first();

        // Tentukan type otomatis
        $type = $last && $last->type === 'in' ? 'out' : 'in';

        // =============================
        // ❌ CEK AGAR TIDAK BISA 2X CHECK-IN
        // =============================
        if ($type === 'in') {
            $sudahCheckIn = Attendance::where('user_id', $user->id)
                ->whereDate('scanned_at', $now->toDateString())
                ->where('type', 'in')
                ->exists();

            if ($sudahCheckIn) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda sudah check-in hari ini'
                ], 422);
            }
        }



        // =============================
        // ⛔ CEGAH CHECK-IN SEBELUM WAKTU
        // =============================
        if ($type === 'in' && $now->lt($checkInTime)) {
            return response()->json([
                'status' => 'error',
                'message' => "Belum waktunya check-in. Buka jam " . $checkInTime->format('H:i')
            ], 422);
        }

        // =============================
        // ⛔ CEGAH CHECK-OUT SEBELUM WAKTU
        // =============================
        if ($type === 'out' && $now->lt($checkOutTime)) {
            return response()->json([
                'status' => 'error',
                'message' => "Belum waktunya check-out. Buka jam " . $checkOutTime->format('H:i')
            ], 422);
        }

        // =============================
        // ✔ SIMPAN ABSENSI
        // =============================
        $attendance = Attendance::create([
            'user_id'    => $user->id,
            'rfid'       => $rfid,
            'type'       => $type,
            'scanned_at' => $now,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => "Berhasil check-$type pada " . $now->format('H:i'),
            'data'    => [
                'user'       => $user,              // << TAMBAH INI
                'type'       => $type,
                'scanned_at' => $attendance->scanned_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }




    public function settings()
    {
        $setting = AttendanceSetting::first();
        return view('attendance.settings', compact('setting'));
    }

    public function updateSettings(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'check_in_time' => 'required',
            'check_out_time' => 'required',
        ]);

        $setting = AttendanceSetting::first();
        if (! $setting) {
            $setting = AttendanceSetting::create($request->only('check_in_time', 'check_out_time'));
        } else {
            $setting->update($request->only('check_in_time', 'check_out_time'));
        }

        return redirect()->back()->with('success', 'Settings updated');
    }

    public function history(Request $request)
    {
        $query = Attendance::with('user');

        // Search by user name or note
        if ($request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhere('note', 'like', '%' . $request->search . '%');
        }

        // Filter by type
        if ($request->type) {
            $query->where('type', $request->type);
        }

        // Paginate length
        $perPage = $request->per_page ?? 10;

        $attendances = $query->orderBy('id', 'DESC')->paginate($perPage);

        return view('attendance.history', compact('attendances'));
    }
}
