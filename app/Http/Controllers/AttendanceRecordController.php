<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LeaveCalender;
use App\Http\Controllers\AttendanceRecordController;
use Carbon\Carbon;
class AttendanceRecordController extends Controller
{
    public function index()
    {
        $users = User::all();
        $currentDate = now()->format('Y-m-d');
        $attendances = AttendanceRecord::where('date', $currentDate)->get();
        return view('attendances.index', compact('users', 'currentDate', 'attendances'));
    }

    public function store(Request $request)
    {
        $user_id = auth()->id();
        $dates = $request->input('date');
        $statuses = $request->input('status');
        $check_ins = $request->input('check_in');
        $check_outs = $request->input('check_out');
        $remarks = $request->input('remarks');

        foreach ($dates as $key => $date) {
            $attendance = AttendanceRecord::where('user_id', $user_id)
                ->whereDate('date', $date)
                ->first();
                if ($attendance) {
                    return redirect()->back()->with('error', 'Attendance has already been submitted for this day.');
                }
                
            $attendance = new AttendanceRecord();
            $attendance->user_id = $user_id;
            $attendance->date = Carbon::createFromFormat('Y-m-d', $date);
            $attendance->status = $statuses[$key] ?? '';
            $attendance->check_in = $check_ins[$key] ?? null;
            $attendance->check_out = $check_outs[$key] ?? date('18:30:00');
            $attendance->remarks = $remarks[$key] ?? '';
            if (!empty($attendance->status) || !empty($attendance->check_in)) {
                if (empty($attendance->check_in)) {
                    return redirect()->back()->with('error', 'Check-in time is required.');
                }
                $attendance->save();
            } else {
                // delete the record if there is no status, check_in, and check_out provided
                $attendance->delete();
            }
        }

        return redirect()->back()->with('success', 'Attendance has been saved.');
    }


    public function generateReport(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $attendances = AttendanceRecord::whereYear('date', $year)
                                        ->whereMonth('date', $month)
                                        ->get();
        return view('attendance.report', [
            'attendances' => $attendances,
            'year' => $year,
            'month' => $month
        ]);
    }
    //yearly report for the admins to view of all the employeess
    public function generateEmployeesReport(Request $request)
    {
        $employeeId = $request->input('employee');
        $year = $request->input('year');
        $month = $request->input('month');
        $employee = User::findOrFail($employeeId);
        
        
        $attendances = AttendanceRecord::where('user_id', $employee)
                                ->whereYear('date', $year)
                                ->whereMonth('date', $month)
                                ->get();

        
        return view('attendance.reportEmployee', [
            'attendances' => $attendances,
            'employee' => $employee,
            'year' => $year,
            'month' => $month
        ]);
    }

    public function edit($id)
    {
        $attendance = AttendanceRecord::find($id);
        return view('attendance.edit', compact('attendance'));
    }

    public function update(Request $request, $id)
    {
        $attendance = AttendanceRecord::find($id);
        $attendance->check_out = $request->input('check_out');
        $attendance->remarks = $request->input('remarks');
        $attendance->save();

        return redirect()->route('dashboard')->with('success', 'Attendance updated successfully');
    }

    public function destroy(Request $request, $id)
    {
        $attendance = AttendanceRecord::findOrFail($id);
        $attendance->delete();

        return redirect()->route('report')->with('success', 'Attendance Record has been deleted');
    }

    public function editRecord($id)
    {
        $attendance = AttendanceRecord::find($id);
        return view('attendance.editRecord', compact('attendance'));
    }
    
    public function updateRecord(Request $request, $id)
    {
        $attendance = AttendanceRecord::find($id);
        $attendance->check_out = $request->input('status');
        $attendance->check_out = $request->input('check_in');
        $attendance->check_out = $request->input('check_out');
        $attendance->remarks = $request->input('remarks');
        $attendance->save();

        return redirect()->route('report')->with('success', 'Attendance updated successfully');
    }
    


}