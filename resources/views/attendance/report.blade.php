<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Attendance Report') }}
        </h2>
    </x-slot>
    @if(session()->has('success'))
        <div>
            <p>{{session('success')}}</p>
        </div>
    @endif
<br>
<!-- To make attendance by the user -->
    <div class="mx-auto add-attendancecss flex flex-col items-center">
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 w-full lg:w-3/4 xl:w-1/2">
            <h3 class="font-semibold text-lg mb-2">View Attendance</h3><br>
            <u><h1>Attendance Report for {{ date('F Y', strtotime($year . '-' . $month . '-01')) }}</h1></u>
            <div class="w-full lg:w-1/3 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 table-container">
                <div class="p-4">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Check in Time</th>
                                <th>Check out Time</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $user_id = auth()->id();
                                // get the selected year and month from the form
                                $year = request()->input('year');
                                $month = request()->input('month');

                                // get the number of days in the selected month
                                $daysInMonth = \Carbon\Carbon::create($year, $month)->daysInMonth;

                                // get the attendance records for the selected year and month
                                $attendances = \App\Models\AttendanceRecord::where('user_id', $user_id)
                                    ->whereMonth('date', $month)
                                    ->whereYear('date', $year)
                                    ->orderBy('date')
                                    ->get();

                                // create an array of the attendance dates for the selected month
                                                $attendanceDates = $attendances->pluck('date')->toArray();
                            @endphp

                            @for($i=1; $i<=$daysInMonth; $i++)
                                @php
                                $date = \Carbon\Carbon::create($year, $month, $i)->format('Y-m-d');

                                    $attendance = $attendances->firstWhere('date', $date);
                                @endphp
            
                                <tr>
                                    <td>{{ $date }}</td>
                                    @if(in_array($date, $attendanceDates))
                                        <td>{{ $attendance->status }}</td>
                                        <td>{{ $attendance->check_in }}</td>
                                        <td>{{ $attendance->check_out }}</td>
                                        <td>{{ $attendance->remarks }}</td>
                                    @else
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                    @endif
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-primary mt-4">{{ __('Back to Dashboard') }}</a>
        </div>
    </div>

</x-app-layout>