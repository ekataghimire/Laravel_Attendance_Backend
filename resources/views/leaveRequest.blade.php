<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Leave Requests') }}
        </h2>
    </x-slot>
    @if(session()->has('success'))
        <div>
            <p>{{session('success')}}</p>
        </div>
    @endif
    <!-- leave record view and send leave request  -->
    <div class=" mx-auto leavecss flex justify-center ">

        <div class="leavecss flex justify-center">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-2">Leave Record</h3>
                <div class="w-1/3 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class=" text-lg mb-9"> <u>Annual Leave Record</u> </h3>
                <p>Total Annual Leave: {{ $leave_categories->where('name', 'annual leave')->first()->annual_entitlement }} days</p>
                <p>Annual Leave taken: {{ $leave_requests_user->where('category_id', $leave_categories->where('name', 'annual leave')->first()->id)->where('status', 'approved')->sum('duration') }} days</p>
                <p>Total Annual Leave remaining: {{ $leave_categories->where('name', 'annual leave')->first()->annual_entitlement - $leave_requests_user->where('category_id', $leave_categories->where('name', 'annual leave')->first()->id)->where('status', 'approved')->sum('duration') }} days</p>
                <br>
                <h3 class=" text-lg mb-9"> <u>Sick Leave Record</u> </h3>
                <p>Total Sick Leave: {{ $leave_categories->where('name', 'sick leave')->first()->annual_entitlement }} days</p>
                <p>Sick Leave taken: {{ $leave_requests_user->where('category_id', $leave_categories->where('name', 'sick leave')->first()->id)->where('status', 'approved')->sum('duration') }} days</p>
                <p>Total Sick Leave remaining: {{ $leave_categories->where('name', 'sick leave')->first()->annual_entitlement - $leave_requests_user->where('category_id', $leave_categories->where('name', 'sick leave')->first()->id)->where('status', 'approved')->sum('duration') }} days</p>
                <br>
                <h3 class=" text-lg mb-9"> <u>Unpaid Leave Record</u> </h3>
                <p>Total unpaid Leave: {{ $leave_categories->where('name', 'unpaid leave')->first()->annual_entitlement }} days</p>
                <p>Unpaid Leave taken: {{ $leave_requests_user->where('category_id', $leave_categories->where('name', 'unpaid leave')->first()->id)->where('status', 'approved')->sum('duration') }} days</p>
                <p>Total unpaid Leave remaining: {{ $leave_categories->where('name', 'unpaid leave')->first()->annual_entitlement - $leave_requests_user->where('category_id', $leave_categories->where('name', 'unpaid leave')->first()->id)->where('status', 'approved')->sum('duration') }} days</p>
                </div>
            </div>
        </div>

        <!-- leave request sent to the database  -->
        <div class="leave-requestcss bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
            <div class="w-full lg:w-3/4 xl:w-1/2">
            <h3 class="font-semibold text-lg mb-2">Leave Request</h3>
            <form method="POST" action="{{ route('store/leave-requests.store') }}" class="form">
                <!-- Form fields go here -->
                @csrf            
                <div class="w-40">
                    <!-- Start Date -->   
                    <div class="w-60">
                        <br>
                        <x-input-label for="start-date" :value="__('Start Date')" />
                        <x-text-input id="start-date" class="block mt-1 w-full" type="date" name="start-date" :value="old('start-date')" required autofocus autocomplete="start-date" />
                        <x-input-error :messages="$errors->get('start-date')" class="mt-2" />
                    </div>
                    <!-- End Date -->                
                    <div class="w-60">
                        <x-input-label for="end-date" :value="__('End Date')" />
                        <x-text-input id="end-date" class="block mt-1 w-full" type="date" name="end-date" :value="old('end-date')" required autofocus autocomplete="end-date" />
                        <x-input-error :messages="$errors->get('end-date')" class="mt-2" />
                    </div>
                    <!-- Leave Type -->                
                    <div class="w-60">
                        <x-input-label for="leave-type" :value="__('Leave Type')" />
                        <select id="leave-type" name="leave-type" class="block mt-1 w-full" :value="old('leave-type')" required autofocus autocomplete="leave-type">
                            @foreach ($leave_categories as $category)
                                <option value="{{ $category->name }}">{{ $category->name }}</option>
                            @endforeach
                        </select>

                    </div>
                    <!-- Reason -->                
                    <div class="w-60">
                        <x-input-label for="reason" :value="__('Reason')" />
                        <x-text-input id="reason" class="block mt-1 w-full" type="text" name="reason" :value="old('reason')" required autofocus autocomplete="reason" />
                        <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">submit</button>
                </div>
            </form>  
            </div>
        </div>
        
    </div>
    @can('superadmin')
        <!-- View Leave request of all the employees -->
            <div class=" mx-auto leavecss flex justify-center">
                <div class="leave-requestcss bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="w-full lg:w-3/4 xl:w-1/2">
                        <h3 class="font-semibold text-lg mb-2">View and Respond to the Leave Request</h3>
                        <div class="p-4 table-responsive">
                            <table class="table-auto min-w-full">
                            @if ($leave_requests->isEmpty())
                                There is no single Leave Request at the moment
                            @else
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>User ID</th>
                                        <th>Name</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Leave Type</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($leave_requests as $leave_request)
                                    <tr>
                                    <td><a href="{{ route('leave-requests.respond', $leave_request->id) }}" class="text-red-500">Respond</a></td>
                                        <td>{{ $leave_request->user->id }}</td>
                                        <td>{{ $leave_request->user->first_name }} {{ $leave_request->user->last_name }}</td>
                                        <td>{{ $leave_request->start_date }}</td>
                                        <td>{{ $leave_request->end_date }}</td>
                                        <td>{{ $leave_request->leaveCategory->name }}</td>
                                        <td>{{ $leave_request->reason }}</td>
                                        <td>{{ $leave_request->status }}</td> <!-- Display the status -->
                                        
                                    </tr>
                                    @endforeach
                                </tbody>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    @endcan

    @can('admin')
        <!-- View Leave request of all the employees -->
            <div class=" mx-auto leavecss flex justify-center">
                <div class="leave-requestcss bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="w-full lg:w-3/4 xl:w-1/2">
                        <h3 class="font-semibold text-lg mb-2">View the Leave Request of all the Employees </h3>
                        <div class="p-4 table-responsive">
                            <table class="table-auto min-w-full">
                            @if ($leave_requests->isEmpty())
                                There is no single Leave Request at the moment
                            @else
                                <thead>
                                    <tr>
                                        <th>User ID</th>
                                        <th>Name</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Leave Type</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($leave_requests as $leave_request)
                                    <tr>
                                        <td>{{ $leave_request->user->id }}</td>
                                        <td>{{ $leave_request->user->first_name }} {{ $leave_request->user->last_name }}</td>
                                        <td>{{ $leave_request->start_date }}</td>
                                        <td>{{ $leave_request->end_date }}</td>
                                        <td>{{ $leave_request->leaveCategory->name }}</td>
                                        <td>{{ $leave_request->reason }}</td>
                                        <td>{{ $leave_request->status }}</td> <!-- Display the status -->
                                        
                                    </tr>
                                    @endforeach
                                </tbody>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    @endcan

    <div class=" mx-auto leavecss flex justify-center ">
        <div class="leave-requestcss bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
            <div class="w-full lg:w-3/4 xl:w-1/2 ">
                <h3 class="font-semibold text-lg mb-2">View your Leave Request</h3>
                <div class="p-4 table-responsive">
                    <table class="table-auto min-w-full">
                        @if ($leave_requests->isEmpty())
                                    You have not Sent a Leave Request Yet.
                        @else
                        <thead>
                            <tr>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Leave Type</th>
                                <th>Reason</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                                @foreach ($leave_requests as $leave_request)
                                    @if ($leave_request->user_id == Auth::id())
                                        <tr>
                                            <td>{{ $leave_request->start_date }}</td>
                                            <td>{{ $leave_request->end_date }}</td>
                                            <td>{{ $leave_request->leaveCategory->name }}</td>
                                            <td>{{ $leave_request->reason }}</td>
                                            <th>{{ $leave_request->status }}</th>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>