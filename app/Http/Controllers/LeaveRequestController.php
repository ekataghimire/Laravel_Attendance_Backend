<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\User;
use App\Models\LeaveCategory;
use Carbon\Carbon;

class LeaveRequestController extends Controller
{
    public function index()
    {
        // Retrieve all leave requests for all users
        $leave_requests = LeaveRequest::with('user')->get();
        
        // Retrieve all leave categories
        $leave_categories = LeaveCategory::all(); 

        // Retrieve leave requests for authenticated user
        $user_id = auth()->id();
        $leave_requests_user = LeaveRequest::where('user_id', $user_id)->get();

        // calculate the duration for each leave request
        foreach ($leave_requests as $leave_request) {
            $start_date = Carbon::parse($leave_request->start_date);
            $end_date = Carbon::parse($leave_request->end_date);
            $duration = $end_date->diffInDays($start_date);
            $leave_request->duration = $duration;
        }

        foreach ($leave_requests_user as $leave_request_user) {
            $start_date = Carbon::parse($leave_request_user->start_date);
            $end_date = Carbon::parse($leave_request_user->end_date);
            $duration = $end_date->diffInDays($start_date);
            $leave_request_user->duration = $duration;
        }
            
        return view('leaveRequest', compact('leave_requests', 'leave_requests_user',  'leave_categories'));
    }


    public function store(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'start-date' => ['required', 'date'],
            'end-date' => ['required', 'date', 'after_or_equal:start-date'],
            'reason' => ['required', 'string'],
            'leave-type' => ['required', 'string'],
        ]);

        // Get the category record that matches the name provided in the form
        $category = LeaveCategory::where('name', $validatedData['leave-type'])->first();

        // Create a new LeaveRequest record
        $leaveRequest = new LeaveRequest;
        $leaveRequest->start_date = $validatedData['start-date'];
        $leaveRequest->end_date = $validatedData['end-date'];
        $leaveRequest->reason = $validatedData['reason'];
        $leaveRequest->status = 'pending'; // Set the status to pending
        $leaveRequest->user_id = auth()->user()->id; // assuming you have a user_id column in your leave_requests table
        $leaveRequest->category_id = $category->id; // Set the category_id to the id of the matching category
        $leaveRequest->save();

        // Redirect back to the dashboard with a success message
        return redirect()->back()->with('success', 'Leave request submitted successfully.');
    }

    public function create()
    {
        $leave_categories = LeaveCategory::all();
        return view('leaveRequest', compact('leave_categories'));
    }


    public function respond($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);

        return view('respond', compact('leaveRequest'));
    }
  
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
        ]);
        
        $leaveRequest = LeaveRequest::findOrFail($id);
        $leaveRequest->status = $request->input('status');
        $leaveRequest->reason = $request->input('reason');
        $leaveRequest->save();
        
        return redirect()->back()->with('success', 'Leave request responded successfully.');
    }


}
