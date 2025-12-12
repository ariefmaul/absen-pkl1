<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsenExport;

use App\Exports\AttendanceExport;




class AdminController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        $totalUsers = User::count();
        $todayAttendance = Attendance::whereDate('scanned_at', now()->format('Y-m-d'))->count();
        $totalAttendance = Attendance::count();

        return view('admin.dashboard', compact('totalUsers', 'todayAttendance', 'totalAttendance'));
    }

    // User Management - List
    public function userIndex(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('rfid', 'like', '%' . $request->search . '%');
        }

        $users = $query->paginate(10);

        if ($request->ajax()) {
            return response()->json($users->items());
        }

        return view('admin.users.index', compact('users'));
    }

    // User Management - Create
    public function userCreate()
    {
        return view('admin.users.create');
    }

    // User Management - Store
    public function userStore(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sekolah' => ['required', 'string', 'max:255'],
            'rfid' => ['required', 'string', 'unique:users,rfid'],
        ]);

        User::create([
            'name' => $request->name,
            'sekolah' => $request->sekolah,
            'rfid' => $request->rfid,
            'password' => Hash::make('password123'), // Default password
        ]);

        if ($request->ajax()) {
            return response()->json(['message' => 'User berhasil ditambahkan'], 201);
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    // User Management - Edit
    public function userEdit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // User Management - Update
    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sekolah' => ['required', 'string', 'max:255'],
            'rfid' => ['required', 'string', 'unique:users,rfid,' . $id],
        ]);

        $user->update($request->only('name', 'sekolah', 'rfid'));

        if ($request->ajax()) {
            return response()->json(['message' => 'User berhasil diperbarui'], 200);
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui');
    }

    // User Management - Delete
    public function userDelete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        if (request()->ajax()) {
            return response()->json(['message' => 'User berhasil dihapus'], 200);
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus');
    }

    // Attendance History
    public function attendanceHistory(Request $request)
    {
        $query = Attendance::with('user');

        if ($request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->month) {
            [$year, $month] = explode('-', $request->month);
            $query->whereYear('scanned_at', $year)
                ->whereMonth('scanned_at', $month);
        }

        $attendances = $query->orderBy('scanned_at', 'desc')->paginate(20);

        // ➕ Tambahkan perhitungan total waktu kerja
        foreach ($attendances as $attendance) {
            if ($attendance->check_in && $attendance->check_out) {
                $in = strtotime($attendance->check_in);
                $out = strtotime($attendance->check_out);
                $diff = $out - $in;

                $hours = floor($diff / 3600);
                $minutes = floor(($diff % 3600) / 60);

                $attendance->total_time = sprintf('%02d , %02d ', $hours, $minutes);
            } else {
                $attendance->total_time = '-';
            }
        }

        return view('admin.attendance.history', compact('attendances'));
    }


    // Manual Attendance - Form
    public function attendanceManualForm()
    {
        $users = User::orderBy('name')->get();
        return view('admin.attendance.manual', compact('users'));
    }

    // Manual Attendance - Store
    public function attendanceManualStore(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'check_in' => ['required', 'date_format:H:i'],
            'check_out' => ['nullable', 'date_format:H:i'],
            'scanned_at' => ['required', 'date'],
            'note' => ['nullable', 'string'],
        ]);

        $attendance = Attendance::create([
            'user_id' => $request->user_id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'scanned_at' => $request->scanned_at . ' ' . $request->check_in,
            'rfid' => null,
            'note' => $request->note,
        ]);

        return redirect()->route('admin.attendance.history')->with('success', 'Absen manual berhasil ditambahkan');
    }

    // Quick Check-In (Manual)
    public function quickCheckIn(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $today = now()->format('Y-m-d');
        $currentTime = now()->format('H:i:s');

        // Cek apakah user sudah absen hari ini
        $attendance = Attendance::where('user_id', $request->user_id)
            ->whereDate('scanned_at', $today)
            ->first();

        if ($attendance) {
            if ($request->ajax()) {
                return response()->json(['message' => 'User sudah melakukan absen hari ini'], 400);
            }
            return back()->with('error', 'User sudah melakukan absen hari ini');
        }

        // Buat absensi baru
        Attendance::create([
            'user_id' => $request->user_id,
            'check_in' => $currentTime,
            'check_out' => null,
            'scanned_at' => now(),
            'rfid' => null,
            'note' => 'Absen manual (masuk)',
        ]);

        if ($request->ajax()) {
            return response()->json(['message' => 'Absen masuk berhasil'], 201);
        }
        return back()->with('success', 'Absen masuk berhasil');
    }

    // Quick Check-Out (Manual)
    public function quickCheckOut(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $today = now()->format('Y-m-d');
        $currentTime = now()->format('H:i:s');

        // Cek apakah user sudah absen masuk hari ini
        $attendance = Attendance::where('user_id', $request->user_id)
            ->whereDate('scanned_at', $today)
            ->first();

        if (!$attendance) {
            if ($request->ajax()) {
                return response()->json(['message' => 'User belum melakukan absen masuk'], 400);
            }
            return back()->with('error', 'User belum melakukan absen masuk');
        }

        if ($attendance->check_out) {
            if ($request->ajax()) {
                return response()->json(['message' => 'User sudah melakukan absen keluar'], 400);
            }
            return back()->with('error', 'User sudah melakukan absen keluar');
        }

        // Update absensi dengan check_out
        $attendance->update([
            'check_out' => $currentTime,
        ]);

        if ($request->ajax()) {
            return response()->json(['message' => 'Absen keluar berhasil'], 200);
        }
        return back()->with('success', 'Absen keluar berhasil');
    }
    public function exportExcel(Request $request)
    {
        $query = Attendance::with('user');

        if ($request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->month) {
            $query->whereMonth('scanned_at', date('m', strtotime($request->month)))
                ->whereYear('scanned_at', date('Y', strtotime($request->month)));
        }

        $attendances = $query->get();

        return Excel::download(new AbsenExport($attendances), 'riwayat_absensi.xlsx');
    }
}
