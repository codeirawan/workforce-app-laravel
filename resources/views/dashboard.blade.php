@extends('layouts.app')

@section('title')
    {{ __('Dashboard') }} | {{ config('app.name') }}
@endsection

@section('subheader')
    {{ __('Dashboard') }}
@endsection

@section('content')
    <div class="kt-portlet">
        <div class="kt-portlet__body">
            <div class="kt-portlet__content">
                @include('layouts.inc.alert')
                @if ($getSchedules->isEmpty())
                    {{ __('Hi') }} {{ auth()->user()->name }}
                @else
                    <div class="card card-custom gutter-b">
                        <div class="form-group mb-0">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" id="filter" placeholder="Search agent name...">
                            </div>
                        </div>

                        @php
                            // Initialize an empty multidimensional array to store agent shifts by date
                            $agentShiftsByDate = [];
                            
                            // Iterate through $getSchedules to collect agent shifts by date
                            foreach ($getSchedules as $schedule) {
                                $agentNik = $schedule->nik;
                                $date = date('d-m-y', strtotime($schedule->date));
                                $shift = $schedule->shift;
                            
                                // Create a new array for the agent if it doesn't exist
                                if (!isset($agentShiftsByDate[$agentNik])) {
                                    $agentShiftsByDate[$agentNik] = [];
                                }
                            
                                // Assign the shift for the date to the agent
                                $agentShiftsByDate[$agentNik][$date] = $shift;
                            }
                        @endphp

                        @php
                            // Collect unique dates from $getSchedules
                            $uniqueDates = [];
                            foreach ($getSchedules as $schedule) {
                                $date = date('d-m-y', strtotime($schedule->date));
                                if (!in_array($date, $uniqueDates)) {
                                    $uniqueDates[] = $date;
                                }
                            }
                        @endphp


                        <div class="card-body table-responsive">
                            <table class="table table-striped table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Gender</th>
                                        <th>Religion</th>
                                        @foreach ($uniqueDates as $date)
                                            <th>{{ $date }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Loop through unique agents and display agent details -->
                                    @foreach ($agentShiftsByDate as $agentNik => $agentShifts)
                                        @php
                                            // Retrieve agent details based on $agentNik
                                            $agentDetails = \App\User::where('nik', $agentNik)->first();
                                        @endphp

                                        <tr class="schedule-row"
                                            data-filter="{{ $agentDetails->nik }} {{ $agentDetails->name }} {{ $agentDetails->gender }} {{ $agentDetails->religion }}">
                                            <td>{{ $agentDetails->nik }}</td>
                                            <td>{{ $agentDetails->name }}</td>
                                            <td>
                                                @if ($agentDetails->gender === 'Male')
                                                    <span class="badge badge-primary">Male</span>
                                                @else
                                                    <span class="badge badge-danger">Female</span>
                                                @endif
                                            </td>
                                            <td>{{ $agentDetails->religion }}</td>
                                            @foreach ($uniqueDates as $date)
                                                <td>
                                                    @if (isset($agentShifts[$date]))
                                                        {{ $agentShifts[$date] }}
                                                    @else
                                                        No Shift
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>


                    </div>
                @endif
            </div>
        </div>
    </div>
    <script>
        // Get the input element
        const filterInput = document.getElementById('filter');

        // Get all rows in the table
        const rows = document.querySelectorAll('.schedule-row');

        // Add an event listener to the input field
        filterInput.addEventListener('input', function() {
            const filterText = this.value.toLowerCase();

            // Loop through rows and hide those that don't match the filter text
            rows.forEach(row => {
                const rowData = row.getAttribute('data-filter').toLowerCase();
                if (rowData.includes(filterText)) {
                    row.style.display = ''; // Show the row
                } else {
                    row.style.display = 'none'; // Hide the row
                }
            });
        });
    </script>
@endsection
