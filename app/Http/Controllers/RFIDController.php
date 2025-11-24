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
        $request->validate([
            'rfid' => 'required'
        ]);

        $user = User::where('rfid', $request->rfid)->first();

        if (!$user) {
            return response()->json([
                'message' => 'RFID tidak ditemukan.'
            ], 404);
        }

        $today = now()->format('Y-m-d');
        $currentTime = now()->format('H:i:s');

        // Ambil setting attendance
        $setting = AttendanceSetting::first(); // ambil setting pertama
        if (!$setting) {
            return response()->json([
                'message' => 'Belum ada pengaturan jam kerja.'
            ], 400);
        }

        $checkInStart = now()->setTimeFromTimeString($setting->check_in_time)->subMinutes(15)->format('H:i:s'); // toleransi ±15 menit
        $checkInEnd   = now()->setTimeFromTimeString($setting->check_in_time)->addMinutes(15)->format('H:i:s');
        $checkOutTime = $setting->check_out_time;

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('scanned_at', $today)
            ->first();

        // Belum ada attendance => Check In
        if (!$attendance) {
            if ($currentTime >= $checkInStart) {
                $attendance = Attendance::create([
                    'user_id' => $user->id,
                    'rfid' => $request->rfid,
                    'check_in' => $currentTime,
                    'scanned_at' => now(),
                    'note' => null,
                ]);

                return response()->json([
                    'data' => [
                        'user' => $user,
                        'type' => 'Absen Masuk',
                        'scanned_at' => $attendance->check_in
                    ]
                ]);
            } else {
                return response()->json([
                    'message' => 'Belum waktunya absen masuk.'
                ], 400);
            }
        }

        // Sudah check-in tapi belum check-out => Check Out
        if ($attendance->check_out === null) {
            if ($currentTime >= $checkOutTime) {
                $attendance->update([
                    'check_out' => $currentTime,
                ]);

                return response()->json([
                    'data' => [
                        'user' => $user,
                        'type' => 'Absen Keluar',
                        'scanned_at' => $attendance->check_out
                    ]
                ]);
            } else {
                return response()->json([
                    'message' => 'Belum waktunya check-out.'
                ], 400);
            }
        }

        // Sudah check-in dan check-out
        return response()->json([
            'message' => 'Anda sudah melakukan scan masuk & keluar hari ini.'
        ], 400);
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

        // Search
        if ($request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }
        // Filter berdasarkan bulan (input type="month")
        if ($request->month) {
            // Format input: YYYY-MM
            [$year, $month] = explode('-', $request->month);
            $query->whereYear('scanned_at', $year)
                ->whereMonth('scanned_at', $month);
        }

        // Pagination
        $perPage = $request->per_page ?? 10;

        $attendances = $query->orderBy('id', 'DESC')->paginate($perPage);

        // Hitung jumlah jam kerja
        foreach ($attendances as $a) {
            if ($a->check_in && $a->check_out) {
                $in = \Carbon\Carbon::parse($a->check_out);
                $out = \Carbon\Carbon::parse($a->check_in);
                $a->jumlah_jam = $out->floatDiffInHours($in);
            } else {
                $a->jumlah_jam = null;
            }
        }

        return view('attendance.history', compact('attendances'));
    }


    public function user()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('user.index', compact('users'));
    }
    public function createUser()
    {
        return view('user.create');
    }
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'rfid' => 'required|string|unique:users,rfid',
            'sekolah' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'rfid' => $request->rfid,
            'sekolah' => $request->sekolah,
        ]);

        return redirect()->route('attendance.user')->with('success', 'User created successfully.');
    }
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('attendance.user')->with('success', 'User deleted successfully.');
    }
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'rfid' => 'required|string|unique:users,rfid,' . $user->id,
            'sekolah' => 'required|',
        ]);

        $user->update([
            'name' => $request->name,
            'rfid' => $request->rfid,
            'sekolah' => $request->sekolah,
        ]);

        return redirect()->route('attendance.user')->with('success', 'User updated successfully.');
    }
}
