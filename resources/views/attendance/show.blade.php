<div class="mx-auto add-usercss flex flex-col items-center">
    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 w-full lg:w-3/4 xl:w-1/2">
        @if (count($attendance ?? []) > 0)
        <h3 class="font-semibold text-lg mb-2">{{ $attendance[0]->user->first_name }} {{ $attendance[0]->user->last_name }}'s Weekly Attendance Report</h3>
                <table class="w-full table-auto">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendance as $record)
                        <tr>
                            <td>{{ $record->date }}</td>
                            <td>{{ $record->status }}</td>
                            <td>{{ $record->check_in }}</td>
                            <td>{{ $record->check_out }}</td>
                            <td>{{ $record->remarks }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No attendance records found for this user.</p>
        @endif
    </div>
</div>
