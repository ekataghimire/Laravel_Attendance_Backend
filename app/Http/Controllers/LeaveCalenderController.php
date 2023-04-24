<?php

namespace App\Http\Controllers;
use App\Models\LeaveCalender;
use App\Models\LeaveCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveCalenderController extends Controller
{

    public function leaveCalender()
    {
        $leaveCalenders = LeaveCalender::all();
        $leaveCategories = LeaveCategory::all(); // get all leave categories
        return view('leaveCalender', ['leaveCalenders' => $leaveCalenders, 'leaveCategories' => $leaveCategories]);
    }


    public function storeHoliday(Request $request)
    {
        $leaveCalender = new LeaveCalender();
        $leaveCalender->name = $request->name;
        $leaveCalender->start_date = $request->start_date;
        $leaveCalender->end_date = $request->end_date;
        $leaveCalender->save();

        return redirect()->route('leaveCalender')->with('success', 'Holiday Added successfully.');
    }

    public function storeLeaveCategory(Request $request)
    {

        $leaveCategory = new LeaveCategory();
        $leaveCategory->name = $request->name;
        $leaveCategory->annual_entitlement = $request->annual_entitlement;
        $leaveCategory->save();

        return redirect()->route('leaveCalender')->with('success', 'Leave Category Added successfully.');
    }

}
