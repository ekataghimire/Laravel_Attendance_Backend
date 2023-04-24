<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Details on Leave and Holiday') }}
        </h2>
    </x-slot>
    @if(session()->has('success'))
        <div>
            <p>{{session('success')}}</p>
        </div>
    @endif
  
    <div class="mx-auto add-usercss flex flex-col items-center ">
        <!-- To view all the Holidays Details  -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 w-full lg:w-3/4 xl:w-1/2">
                <div class="tooltip"><h3 class="font-semibold text-lg mb-2 toggle-table">View Holidays Details</h3>
                    <span class="tooltiptext">Click here to view all the details of the Holidays</span>
                </div>
                <div class="w-full lg:w-1/3 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 table-container hidden">
                    <div class="p-4">
                        <div class="table-responsive">
                            <table class="table-auto min-w-full">
                                <thead>
                                    <tr>
                                    <th>Holiday Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Number of holidays</th>
                                    </tr>   
                                </thead>
                                <tbody>
                                @foreach($leaveCalenders as $leaveCalender)
                                    @if($leaveCalender->name != 'Weekly Holiday')
                                        <tr>
                                            <td>{{ $leaveCalender->name }}</td>
                                            <td>{{ $leaveCalender->start_date }}</td>
                                            <td>{{ $leaveCalender->end_date }}</td>
                                            <td>{{ \Carbon\Carbon::parse($leaveCalender->start_date)->diffInDays(\Carbon\Carbon::parse($leaveCalender->end_date)) + 1 }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <!-- To Add the Holiday Details  -->
            @cannot('user')
            <div class="bg-white dark:bg-gray-800 holidaycss shadow-sm sm:rounded-lg p-6 w-full lg:w-3/4 xl:w-1/2">
                <div class="tooltip">
                    <h3 class="font-semibold text-lg mb-2 toggle-form">Add Holiday</h3>
                    <span class="tooltiptext">Click here to add a new holiday</span>
                </div>
                <div class="w-full lg:w-1/3 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 form-container hidden">
                    <div class="p-4">
                        <form method="POST" action="{{ route('storeHoliday') }}">
                            @csrf
                            <div class="form-group">
                                <label for="name">Holiday Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="start_date">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>
                            <div class="form-group">
                                <label for="end_date">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Holiday</button>
                        </form>
                    </div>
                </div>
            </div>
            @endcannot
    </div>
    <div class="mx-auto add-usercss flex flex-col items-center ">
        <!-- To view all the Leave Category Details  -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 w-full lg:w-3/4 xl:w-1/2">
                <div class="tooltip"><h3 class="font-semibold text-lg mb-2 toggle-table2">View Leave Categories Details</h3>
                    <span class="tooltiptext">Click here to view all the details of the Leave categories</span>
                </div>
                <div class="w-full lg:w-1/3 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 leaveCategory table-container hidden">
                    <div class="p-4">
                        <div class="table-responsive">
                            <table class="table-auto min-w-full">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Annual Entitlement</th>
                                    </tr>   
                                </thead>
                                <tbody>
                                    @foreach($leaveCategories as $leaveCategory)
                                    <tr>
                                        <td>{{ $leaveCategory->name }}</td>
                                        <td>{{ $leaveCategory->annual_entitlement }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <!-- To Add the Leave Categories Details  -->
        @cannot('user')
            <div class="bg-white dark:bg-gray-800 holidaycss shadow-sm sm:rounded-lg p-6 w-full lg:w-3/4 xl:w-1/2">
                <div class="tooltip">
                    <h3 class="font-semibold text-lg mb-2 toggle-form2">Add Leave Categories</h3>
                    <span class="tooltiptext">Click here to add a new Leave Category</span>
                </div>
                <div class="w-full lg:w-1/3 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 form-container2 hidden">
                    <div class="p-4">
                    <form method="POST" action="{{ route('storeLeaveCategory') }}">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="annual_entitlement">Annual Entitlement</label>
                            <input type="number" class="form-control" id="annual_entitlement" name="annual_entitlement" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Leave Category</button>
                    </form>

                    </div>
                </div>
            </div>
        @endcannot
    </div>
    

    <script>
        // Get the "Holidays Details" heading
        const toggleTableHeading = document.querySelector('.toggle-table');
        // Get the table container
        const tableContainer = document.querySelector('.table-container');
        // Add a click event listener to the heading
        toggleTableHeading.addEventListener('click', () => {
            // Toggle the visibility of the table container
            tableContainer.classList.toggle('hidden');
        });
        
         // Get the "Leave Category Details" heading
         const toggleTable2Heading = document.querySelector('.toggle-table2');
        // Get the table container
        const table2Container = document.querySelector('.leaveCategory');
        // Add a click event listener to the heading
        toggleTable2Heading.addEventListener('click', () => {
            // Toggle the visibility of the table container
            table2Container.classList.toggle('hidden');
        });

        // Get the "Add Holiday" heading
        const toggleFormHeading = document.querySelector('.toggle-form');
        // Get the form container
        const formContainer = document.querySelector('.form-container');
        // Add a click event listener to the heading
        toggleFormHeading.addEventListener('click', () => {
            // Toggle the visibility of the form container
            formContainer.classList.toggle('hidden');
        });

        // Get the "Add Leave Categories" heading
        const toggleFormHeading2 = document.querySelector('.toggle-form2');
        // Get the form container
        const formContainer2 = document.querySelector('.form-container2');
        // Add a click event listener to the heading
        toggleFormHeading2.addEventListener('click', () => {
            // Toggle the visibility of the form container
            formContainer2.classList.toggle('hidden');
        });
    </script>
    
    @if(session('error'))
        <script>
            alert('{{ session('error') }}');
        </script>
    @endif

</x-app-layout>