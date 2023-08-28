@extends('layouts.app')

@section('title')
    {{ __('Schdedule') }} | {{ config('app.name') }}
@endsection

@section('style')
    <link href="{{ asset(mix('css/datatable.css')) }}" rel="stylesheet">
@endsection

@section('subheader')
    {{ __('Schedule') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('schedule.index') }}" class="kt-subheader__breadcrumbs-link">{{ __('Create schedules easily') }}</a>
@endsection

@section('content')
    <div class="card card-custom my-3">
        <div class="card-header">
            <div class="card-toolbar">
                <ul class="nav nav-light-danger nav-bold nav-pills">
                    <li class="nav-item">
                        <a class="nav-link active content" data-toggle="tab" href="#schedule">
                            <span class="nav-icon"><i class="fa-solid fa-calendar-check"></i></span>
                            <span class="nav-text">Schedule</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link quiz" data-toggle="tab" href="#agent">
                            <span class="nav-icon"><i class="fa-solid fa-users"></i></span>
                            <span class="nav-text">Agent</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link participant" data-toggle="tab" href="#shift">
                            <span class="nav-icon"><i class="fa-solid fa-clock"></i></span>
                            <span class="nav-text">Shift</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link chart" data-toggle="tab" href="#forecast">
                            <span class="nav-icon"><i class="fa-solid fa-chart-simple"></i></span>
                            <span class="nav-text">Forecast</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="schedule" role="tabpanel">
                <div class="kt-portlet my-0">
                    <div class="kt-portlet__head kt-portlet__head--lg">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">{{ __('Rostering Shift') }}</h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <a href="{{ route('schedule.index') }}" class="btn btn-secondary kt-margin-r-10">
                                <i class="la la-arrow-left"></i>
                                <span class="kt-hidden-mobile">{{ __('Back') }}</span>
                            </a>
                            @if (Laratrust::isAbleTo('create-schedule'))
                                <form id="generate-form" action="{{ route('schedule.generate', $scheduling->id) }}"
                                    method="POST" style="display: none;">
                                    @csrf
                                </form>

                                <a href="#" class="btn btn-danger"
                                    onclick="event.preventDefault(); document.getElementById('generate-form').submit();">
                                    <i class="fa-solid fa-wand-magic-sparkles"></i> {{ __('Generate Schedule') }}
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-portlet__content">
                            @include('layouts.inc.alert')
                            <div class="card card-custom gutter-b mb-4">
                                <div class="bg-secondary text-dark">
                                    <div class="card-title">
                                        <h6 class="card-label mt-2 ml-4 p-2">
                                            Generate Schedule
                                        </h6>
                                    </div>
                                </div>

                                <div class="card-body table-responsive">
                                    <table class="table table-striped table-bordered text-center">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Religion</th>
                                                <th>Day</th>
                                                <th>Date</th>
                                                <th>Shift</th>
                                                <th>Start</th>
                                                <th>End</th>
                                                <!-- Loop through intervals -->
                                                @for ($hour = 6; $hour < 29; $hour++)
                                                    @for ($minute = 0; $minute < 60; $minute += 60)
                                                        @php
                                                            $formattedHour = sprintf('%02d', $hour % 24);
                                                            $nextHour = sprintf('%02d', ($hour + 1) % 24);
                                                            $formattedMinute = sprintf('%02d', $minute);
                                                            $formattedNextMinute = sprintf('%02d', ($minute + 60) % 60);
                                                        @endphp
                                                        <th colspan="4">{{ $formattedHour }}:{{ $formattedMinute }}
                                                            {{-- @if ($formattedNextMinute == '00')
                                                                {{ $nextHour }}:00
                                                            @else
                                                                {{ $formattedHour }}:{{ $formattedNextMinute }}
                                                            @endif --}}
                                                        </th>
                                                    @endfor
                                                @endfor
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Loop through schedules and display agent details -->
                                            @foreach ($getSchedules as $schedule)
                                                @php
                                                    $scheduleStartHour = (int) substr($schedule->start_time, 0, 2);
                                                    $scheduleEndHour = (int) substr($schedule->end_time, 0, 2);
                                                @endphp
                                                <tr class="schedule-row">
                                                    <td>{{ $schedule->nik }}</td>
                                                    <td>{{ $schedule->name }}</td>
                                                    <td>
                                                        @if ($schedule->gender === 'Male')
                                                            <span class="badge badge-primary">Male</span>
                                                        @else
                                                            <span class="badge badge-danger">Female</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $schedule->religion }}</td>
                                                    {{-- <td>
                                                        @php
                                                            $dayOfWeek = date('D', strtotime($schedule->date));
                                                            $badgeClass = '';
                                                            switch ($dayOfWeek) {
                                                                case 'Mon':
                                                                    $badgeClass = 'badge-primary';
                                                                    break;
                                                                case 'Tue':
                                                                    $badgeClass = 'badge-success';
                                                                    break;
                                                                case 'Wed':
                                                                    $badgeClass = 'badge-info';
                                                                    break;
                                                                case 'Thu':
                                                                    $badgeClass = 'badge-warning';
                                                                    break;
                                                                case 'Fri':
                                                                    $badgeClass = 'badge-danger';
                                                                    break;
                                                                case 'Sat':
                                                                    $badgeClass = 'badge-dark';
                                                                    break;
                                                                case 'Sun':
                                                                    $badgeClass = 'badge-secondary';
                                                                    break;
                                                            }
                                                        @endphp
                                                        <span class="badge {{ $badgeClass }}">{{ $dayOfWeek }}</span>
                                                    </td> --}}
                                                    <td>{{ date('D', strtotime($schedule->date)) }}</td>
                                                    <td>{{ date('d-m-y', strtotime($schedule->date)) }}</td>
                                                    <td>{{ $schedule->shift }}</td>
                                                    <td>{{ date('H:i', strtotime($schedule->start_time)) }}</td>
                                                    <td>{{ date('H:i', strtotime($schedule->end_time)) }}</td>
                                                    <!-- Loop through intervals and apply color range -->
                                                    @for ($hour = 6; $hour < 29; $hour++)
                                                        @for ($minute = 0; $minute < 60; $minute += 15)
                                                            @php
                                                                $formattedHour = sprintf('%02d', $hour % 24);
                                                                $formattedMinute = sprintf('%02d', $minute);
                                                                $currentTime = "$formattedHour:$formattedMinute:00";
                                                                $colorClass = $currentTime >= $schedule->start_time && $currentTime < $schedule->end_time ? 'shift-assigned' : 'shift-not-assigned';
                                                                
                                                                // Calculate the time since the start of the shift in minutes
                                                                $timeSinceStartMinutes = ($hour - $scheduleStartHour) * 60 + $minute;
                                                                
                                                                // Check if it's time for a break
                                                                $isTimeForBreak = $timeSinceStartMinutes >= 180 && $timeSinceStartMinutes < 210;

                                                                // Calculate time until shift ends in minutes
                                                                $scheduleEndHour = (int) substr($schedule->end_time, 0, 2);
                                                                $scheduleEndMinute = (int) substr($schedule->end_time, 3, 2);
                                                                $timeUntilShiftEndsMinutes = $scheduleEndHour * 60 + $scheduleEndMinute - ($hour * 60 + $minute);
                                                                $isTimeForSecondBreak = $timeUntilShiftEndsMinutes <= 120 && $timeUntilShiftEndsMinutes > 90;

                                                                if ($isTimeForBreak || $isTimeForSecondBreak) {
                                                                    $colorClass .= ' break-flag';
                                                                }
                                                            @endphp
                                                            <td class="{{ $colorClass }}"></td>
                                                        @endfor
                                                    @endfor
                                                    <td></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="agent" role="tabpanel">
                <div class="kt-portlet my-0">
                    <div class="kt-portlet__body">
                        <div class="kt-portlet__content">
                            @include('layouts.inc.alert')
                            <div class="card card-custom gutter-b">
                                <div class="bg-secondary text-dark">
                                    <div class="card-title">
                                        <h6 class="card-label mt-2 ml-4 p-2">
                                            Available Agents
                                        </h6>
                                    </div>
                                </div>
                                <div class="card-body table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Agent</th> <!-- Empty header cell for spacing -->
                                                @foreach ($availableAgents as $agent)
                                                    <th>{{ $agent->name }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Gender</td>
                                                @foreach ($availableAgents as $agent)
                                                    <td>{{ $agent->gender }}</td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>Religion</td>
                                                @foreach ($availableAgents as $agent)
                                                    <td>{{ $agent->religion }}</td>
                                                @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="shift" role="tabpanel">
                <div class="kt-portlet my-0">
                    <div class="kt-portlet__body">
                        <div class="kt-portlet__content">
                            @include('layouts.inc.alert')
                            <div class="card card-custom gutter-b">
                                <div class="bg-secondary text-dark">
                                    <div class="card-title">
                                        <h6 class="card-label mt-2 ml-4 p-2">
                                            Shift Variations
                                        </h6>
                                    </div>
                                </div>
                                <div class="card-body table-responsive table-bordered">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Shift</th> <!-- Empty header cell for spacing -->
                                                @foreach ($shifts as $shift)
                                                    <th>{{ $shift->name }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Start</td>
                                                @foreach ($shifts as $shift)
                                                    <td>{{ $shift->start_time }}</td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>End</td>
                                                @foreach ($shifts as $shift)
                                                    <td>{{ $shift->end_time }}</td>
                                                @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="forecast" role="tabpanel">
                <div class="kt-portlet my-0">
                    <div class="kt-portlet__body">
                        <div class="kt-portlet__content">
                            @include('layouts.inc.alert')
                            <div class="card card-custom gutter-b">
                                <div class="bg-secondary text-dark">
                                    <div class="card-title">
                                        <h6 class="card-label mt-2 ml-4 p-2">
                                            Forecast Total Agents Need All Days
                                        </h6>
                                    </div>
                                </div>
                                <div class="card-body table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Interval Per Day</th>
                                                @foreach (range(0, 23) as $hour)
                                                    <th>{{ sprintf('%02d', $hour) }}:00 -
                                                        {{ sprintf('%02d', ($hour + 1) % 24) }}:00
                                                    </th>
                                                @endforeach
                                                <th>Total Agents Need</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($forecastDataAllDays as $dayName => $forecastData)
                                                <tr>
                                                    <td>{{ ucfirst($dayName) }}</td>
                                                    @php
                                                        $total = 0;
                                                    @endphp
                                                    @foreach ($forecastData['forecastedCallResults'] as $forecastResult)
                                                        @php
                                                            $forecastResultArray = (array) $forecastResult;
                                                        @endphp
                                                        @foreach (range(0, 23) as $hour)
                                                            @php
                                                                $hourRange = sprintf('%02d', $hour) . ':00 - ' . sprintf('%02d', ($hour + 1) % 24) . ':00';
                                                                $total += $forecastResultArray[strtolower($hourRange)];
                                                            @endphp
                                                            <td>{{ $forecastResultArray[strtolower($hourRange)] }}</td>
                                                        @endforeach
                                                    @endforeach
                                                    <td>
                                                        {{ $scheduling->reporting_period == 3600 ? round($total / 8, 0) : ($scheduling->reporting_period == 1800 ? round($total / 16, 0) : ($scheduling->reporting_period == 900 ? round($total / 24, 0) : '')) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .shift-assigned {
            background-color: #1dc9b7;
            /* Apply desired color */
        }

        .shift-not-assigned {
            background-color: #E1E1EF;
            /* Apply desired color */
        }

        .break-flag {
            background-color: #FFA500;
            /* Apply desired color for the break flag */
        }
    </style>
@endsection

@section('script')
    <script src="{{ asset(mix('js/datatable.js')) }}"></script>
    <script src="{{ asset(mix('js/tooltip.js')) }}"></script>
    <script src="{{ asset(mix('js/form/validation.js')) }}"></script>
@endsection
