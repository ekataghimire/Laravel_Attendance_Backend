<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Employee Details') }}
        </h2>
    </x-slot>
    @if(session()->has('success'))
        <div>
            <p>{{session('success')}}</p>
        </div>
    @endif
    @cannot('user')
        <div class="p-6">
            <div class="flex flex-wrap">
                <div class="w-full md:w-1/3 px-4 py-2">
                    <!-- Employees Details Display -->
                    <div class="py-6">
                        <div class="w-full px-4 py-2">
                            <a href="{{ route('users.create') }}" class="btn-blue btn btn-primary">Add User</a>
                        </div>
                    </div>
                        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                            <div class="tooltip"><h3 class="font-semibold text-lg mb-2 toggle-table">Edit Employee Details</h3>
                                <span class="tooltiptext">Click here to Edit all the details of the employee</span>
                            </div>
                            <div class="w-1/3 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 table-container hidden">
                                <div class="p-4 table-responsive">
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
                                                <!-- <th>Password</th> -->
                                                <th>Position</th>
                                                <th>Update</th>
                                                <th>Delete</th>
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
                                                    <!-- <td>{{ $user->password }}</td> -->
                                                    <td>{{ $user->position }}</td>
                                                    <td>
                                                        <a href="{{ route('users.edit', $user->id) }}" class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded">
                                                            Edit
                                                        </a>
                                                    </td>
                                                    <td>
                                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="bg-red-500 hover:bg-red-700  font-bold py-2 px-4 rounded">Delete</button>
                                                    </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            
                                        </tbody>
                                    </table>
                                    <tfoot>                                      
                                            <div class="mt-4">
                                                {{ $users->links() }}
                                            </div>
                                    </tfoot>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full px-4 py-2">
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-semibold text-lg mb-2 toggle-table">Another title</h3>
                    </div>
                </div>
            </div>
        </div>
    @endcannot
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
</x-app-layout>
