<?php

namespace App\Http\Controllers;
use App\Models\AttendanceRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function report()
    {
        $users = User::all(); // get all users
        $attendances = attendanceRecord::with('user')
            ->whereDate('date', '=', now()->toDateString()) // filter by today's date
            ->orderBy('date', 'desc')
            ->get();

        return view('report', ['users' => $users, 'attendances' => $attendances]);
    } 
    
    public function show(Request $request)
    {
        $users = User::all(); // get all users
        
        $year = $request->input('year', now()->year); // get year from request, default to current year
        $attendance_records = AttendanceRecord::where('user_id', auth()->id())->whereYear('date', $year)->get();
        
        $monthly_summary = collect();
        $attendances = attendanceRecord::with('user')
            ->whereDate('date', '=', now()->toDateString()) // filter by today's date
            ->orderBy('date', 'desc')
            ->get();

        foreach (range(1, 12) as $month) {
            $present_days = 0;
            $absent_days = 0;

            $month_name = Carbon::create($year, $month, 1)->format('F');
            $days_in_month = Carbon::create($year, $month, 1)->daysInMonth;

            foreach ($attendance_records as $record) {
                $date = Carbon::parse($record->date);
                
                if ($date->month == $month && $record->status == 'present') {
                    $present_days++;
                }
            }

            $absent_days = $days_in_month - $present_days;

            $monthly_summary->push([
                'month' => $month_name,
                'present_days' => $present_days,
                'absent_days' => $absent_days,
            ]);
        }

        return view('report', ['users' => $users, 'monthly_summary' => $monthly_summary, 'attendances' => $attendances, 'year' => $year]);
    }
    // to display users attendance details for the selected week
   public function showWeeklyAttendance(Request $request)
    {
        $employee_id = $request->input('employee');
        if ($employee_id == 'mine') {
            $user_id = Auth::id();
            $attendance = AttendanceRecord::where('user_id', $user_id)
            ->whereBetween('date', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->get();
        } else {
            $attendance = AttendanceRecord::with('user')
                ->where('user_id', $employee_id)
                ->whereBetween('date', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ])->get();
        }
        return view('attendance.show', compact('attendance'));
    }

}
