<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}<h3 class="font-semibold text-lg mb-2 ">{{ __("Welcome, ") }}{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}{{ __("!") }}</h3>
        </h2>
    </x-slot>
    @if(session()->has('success'))
        <div>
            <p>{{session('success')}}</p>
        </div>
    @endif
    @cannot('user')
    <!-- To view all the Employee Details  -->
        <div class="mx-auto add-usercss flex flex-col items-center ">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 w-full lg:w-3/4 xl:w-1/2">
                <div class="tooltip"><h3 class="font-semibold text-lg mb-2 toggle-table">View Employee Details</h3>
                    <span class="tooltiptext">Click here to view all the details of the employee</span>
                </div>
                <div class="w-full lg:w-1/3 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 table-container hidden">
                    <div class="p-4">
                        <div class="table-responsive">
                            <table class="table-auto min-w-full">
                                <thead>
                                    <tr>
                                        <th class="text-left">ID</th>
                                        <th>First Name</th>
                                        <th>Middle Name</th>
                                        <th>Last Name</th>
                                        <th>Number</th>
                                        <th>Address</th>
                                        <th>Email</th>
                                        <th>Email Verified At</th>
                                        <th>Position</th>
                                        <th>User Role</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                    </tr>   
                                </thead>
                                <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->first_name }}</td>
                                        <td>{{ $user->middle_name }}</td>
                                        <td>{{ $user->last_name }}</td>
                                        <td>{{ $user->number }}</td>
                                        <td>{{ $user->address }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->email_verified_at }}</td>
                                        <td>{{ $user->position }}</td>
                                        <td>{{ $user->user_role }}</td>
                                        <td>{{ $user->created_at }}</td>
                                        <td>{{ $user->updated_at }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcannot
    <br>
    <!-- to view the attendance details over years of themselves -->
        <div class="mx-auto add-attendancecss flex flex-col items-center">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 w-full lg:w-3/4 xl:w-1/2">
                <h3 class="font-semibold text-lg mb-2">View Employee Attendance</h3>
                <div class="w-full lg:w-1/3 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 table-container">
                    <div class="p-4">
                        <div class="table-responsive">
                            <form method="POST" action="{{ route('attendance.report') }}">
                                @csrf
                                @php
                                    $today = \Carbon\Carbon::today();
                                    $year = $today->year;
                                    $month = $today->month;
                                    $daysInMonth = $today->daysInMonth;
                                    $holidays = \App\Models\LeaveCalender::whereYear('start_date', $year)
                                                                            ->whereMonth('start_date', $month)
                                                                            ->get(['start_date']);
                                    $holidayDates = $holidays->pluck('start_date');
                                @endphp
                                <div class="form-group">
                                    <label for="year">Year:</label>
                                    <select class="form-control" id="year" name="year">
                                        @for ($i = 2015; $i <= date('Y'); $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <label>Select month:</label>
                                        @foreach (['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $index => $name)
                                            <label class="month-label">
                                                <input type="radio" class="month-radio" name="month" value="{{ $index + 1 }}" {{ $month == $index + 1 ? 'checked' : '' }}>
                                                {{ $name }}
                                            </label>
                                        @endforeach
                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary"><u>Show Attendance</u></button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    <br>
    <br>




<br>

    <!-- To make attendance by the user -->
    <div class="mx-auto add-attendancecss flex flex-col items-center">
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 w-full lg:w-3/4 xl:w-1/2">
            <h3 class="font-semibold text-lg mb-2">Make Attendance</h3>
            <div class="w-full lg:w-1/3 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 table-container">
                <div class="p-4">
                    <div class="table-responsive">
                    <form method="POST" action="{{ route('attendance') }}">
                        @csrf
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Check in Time</th>
                                    <th>Check out Time</th>
                                    <th>Remarks</th>
                                    <th>Submit Record</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $today = \Carbon\Carbon::today();
                                    $year = $today->year;
                                    $month = $today->month;
                                    $daysInMonth = $today->daysInMonth;
                                    $holidays = \App\Models\LeaveCalender::whereYear('start_date', $year)
                                                                        ->whereMonth('start_date', $month)
                                                                        ->get(['start_date']);
                                    $holidayDates = $holidays->pluck('start_date');
                                @endphp

                                @for ($day = 1; $day <= $daysInMonth; $day++)
                                    @php
                                        $date = \Carbon\Carbon::create($year, $month, $day);
                                        $disabled = ($date < $today) || $holidayDates->contains($date->toDateString());

                                        $attendance = \App\Models\AttendanceRecord::where('user_id', auth()->id())
                                                                                ->whereDate('date', $date)
                                                                                ->first();
                                        $status = $attendance ? $attendance->status : '';
                                        $subbtn = '';
                                        $check_in = $attendance ? $attendance->check_in : '';
                                        $check_out = $attendance ? $attendance->check_out : '';
                                        $remarks = $attendance ? $attendance->remarks : '';

                                        $submitDisabled = $disabled || $attendance;
                                    $editDisabled = $disabled || !$attendance || $date > $today;

                                    // New variable to determine if current column is submit column
                                    $isSubmitColumn = $date->toDateString() === $today->toDateString();
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="date" name="date[]" {{ $disabled ? 'disabled' : '' }} value="{{ $date->toDateString() }}">
                                        </td>
                                        <td>
                                            @if ($disabled)
                                            {{ (string)$status ?: '-' }}

                                            @else
                                                <select name="status[]" {{ $date->toDateString() !== $today->toDateString() ? 'disabled' : '' }}>
                                                    <option value="present" {{ $status === 'present' ? 'selected' : '' }}>Present</option>
                                                    <option value="absent" {{ $status === 'absent' ? 'selected' : '' }}>Absent</option>
                                                    <option value="late" {{ $status === 'late' ? 'selected' : '' }}>Late</option>
                                                </select>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($disabled)
                                                {{ (string)$check_in ?: '-' }}
                                            @else
                                            <input type="time" name="check_in[]" value="{{ $check_in }}" {{ $date->toDateString() !== $today->toDateString() ? 'disabled' : '' }} />

                                            @endif
                                        </td>
                                        <td>
                                            @if ($disabled)
                                                {{ (string)$check_out ?: '-' }}
                                            @else
                                                <input type="time" name="check_out[]" value="{{ $check_out }}" {{ $date->toDateString() !== $today->toDateString() ? 'disabled' : '' }} />
                                            @endif
                                        </td>
                                        <td>
                                            @if ($disabled)
                                                {{ (string)$remarks ?: '-' }}
                                            @else
                                                <input type="text" name="remarks[]" value="{{ $remarks }}" {{ $date->toDateString() !== $today->toDateString() ? 'disabled' : '' }} />
                                            @endif
                                        </td>
                                        <td>
                                            @if ($disabled)
                                                {{ (string)$subbtn ?: '-' }}
                                            @else
                                                <button type="submit" {{ $date->toDateString() !== $today->toDateString() ? 'disabled' : '' }}>Submit</button>
                                            @endif
                                        </td>
                                        @if ($day === $today->day)
                                            <td>
                                                
                                            @if ($attendance)
                                                <a href="{{ route('attendance.edit', ['id' => $attendance->id]) }}" data-toggle="modal" data-target="#editAttendanceModal{{ $attendance->id }}" {{ $editDisabled ? 'disabled' : '' }}>Edit</a>
                                                <!-- Edit attendance modal code... -->
                                            @endif
                                            </td>
                                        @endif

                                    </tr>
                                @endfor
                            </tbody>

                        </table>
                    </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Get the "Employee Details" heading
        const toggleTableHeading = document.querySelector('.toggle-table');
        // Get the table container
        const tableContainer = document.querySelector('.table-container');
        // Add a click event listener to the heading
        toggleTableHeading.addEventListener('click', () => {
            // Toggle the visibility of the table container
            tableContainer.classList.toggle('hidden');
        });
    </script>
    @if(session('error'))
    <script>
        alert('{{ session('error') }}');
    </script>
    @endif

</x-app-layout>