<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Today Attendance') }}
        </h2>
    </x-slot>
    @if(session()->has('success'))
        <div>
            <p>{{session('success')}}</p>
        </div>
    @endif
    <div class="p-6">
        <div class="flex flex-wrap ">
            <div class="w-full px-4 py-2 ">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h2>Edit Today's Attendance Record</h2>
                    <div class="w-1/3 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 table-container">
                            <div class="p-4">
                                <form method="POST" action="{{ route('attendance.update', $attendance->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="date">Date</label>
                                        <input type="date" class="form-control" id="date" name="date" value="{{ $attendance->date }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <input type="text" class="form-control" id="status" name="status" value="{{ $attendance->status }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="check_in">Check In Time</label>
                                        <input type="time" class="form-control" id="check_in" name="check_in" value="{{ $attendance->check_in }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="check_out">Check Out Time</label>
                                        <input type="time" class="form-control" id="check_out" name="check_out" value="{{ $attendance->check_out }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="remarks">Remarks</label>
                                        <textarea class="form-control" id="remarks" name="remarks">{{ $attendance->remarks }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
