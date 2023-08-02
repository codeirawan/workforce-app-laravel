@extends('layouts.app')

@section('title')
    {{ __('Full Time Equivalent') }} | {{ config('app.name') }}
@endsection

@section('style')
    <link href="{{ asset(mix('css/datatable.css')) }}" rel="stylesheet">
@endsection

@php
    $startDate = \Carbon\Carbon::parse($params->start_date)->format('d M Y');
    $endDate = \Carbon\Carbon::parse($params->end_date)->format('d M Y');
@endphp

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('forecast.index') }}" class="kt-subheader__breadcrumbs-link">{{ __('Full Time Equivalent') }}
        {{ $startDate }} - {{ $endDate }} ({{ $skill->skill . ' - ' . $skill->project . ' ' . $skill->site }})</a>
@endsection


@section('content')
    <div class="kt-portlet">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">{{ __('Capacity Planning') }}</h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <a href="{{ route('forecast.index') }}" class="btn btn-secondary kt-margin-r-10">
                    <i class="la la-arrow-left"></i>
                    <span class="kt-hidden-mobile">{{ __('Back') }}</span>
                </a>
                @if (Laratrust::isAbleTo('create-forecast'))
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal-add-history">
                        <i class="fa-solid fa-plus"></i> {{ __('Add History') }}
                    </a>
                @endif
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="kt-portlet__content">
                @include('layouts.inc.alert')

                <div id="cards-container"></div>

                @for ($day = 0; $day < 7; $day++)
                    <div class="card card-custom gutter-b mb-4">
                        <div class="bg-secondary text-dark">
                            <div class="card-title">
                                <h6 class="card-label mt-2 ml-4 p-2">
                                    FTE Interval {{ ucfirst(Date('l', strtotime('Monday +' . $day . ' days'))) }}
                                </h6>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered"
                                id="fte_req_{{ strtolower(Date('D', strtotime('Monday +' . $day . ' days'))) }}"></table>
                        </div>
                    </div>
                @endfor

            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('forecast.modal.add-history')
    @include('forecast.modal.edit-adjust')
    @include('layouts.inc.modal.delete', ['object' => 'history'])

    <script src="{{ asset(mix('js/datatable.js')) }}"></script>
    <script src="{{ asset(mix('js/tooltip.js')) }}"></script>
    <script src="{{ asset(mix('js/form/validation.js')) }}"></script>

    <script>
        function generateCard(title, icon) {
            return `
            <div class="card card-custom gutter-b mb-4">
                <div class="bg-secondary text-dark">
                    <div class="card-title">
                        <h6 class="card-label mt-2 ml-4 p-2">${title} <i class="fa-solid ${icon}"></i></h6>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered" id="${title.toLowerCase().replace(/\s+/g, '_')}"></table>
                </div>
            </div>`;
        }

        const historyWeeklyCard = generateCard('History Weekly', 'fa-clock-rotate-left');
        const adjustForecastCard = generateCard('Adjust Forecast', 'fa-sliders');
        const coForecastCard = generateCard('CO Forecast', 'fa-chart-simple');
        document.getElementById('cards-container').innerHTML = historyWeeklyCard + adjustForecastCard + coForecastCard;
    </script>

    <script type="text/javascript">
        // DataTables configuration object
        const dataTableConfig = {
            processing: true,
            serverSide: true,
            language: {
                emptyTable: "{{ __('No data available in table') }}",
                info: "{{ __('Showing _START_ to _END_ of _TOTAL_  entries') }}",
                infoEmpty: "{{ __('Showing 0 to 0 of 0 entries') }}",
                infoFiltered: "({{ __('filtered from _MAX_ total entries') }})",
                lengthMenu: "{{ __('Show _MENU_ entries') }}",
                loadingRecords: "{{ __('Loading') }}...",
                processing: "{{ __('Processing') }}...",
                search: "{{ __('Search') }}",
                zeroRecords: "{{ __('No matching records found') }}"
            },
            order: [
                [1, 'desc']
            ],
            drawCallback: function() {
                $('.btn-tooltip').tooltip();
            },
            initComplete: function() {
                $('.dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate').hide();
            }
        };

        // Common attributes for all columns
        const commonColumnAttributes = {
            defaultContent: '-',
            class: 'text-center',
            searchable: false,
            orderable: false
        };

        // Rounded data value function
        const roundedDataValue = function(data, type) {
            if (type === 'display' && data !== '-') {
                const roundedData = Math.round(data);
                const formattedData = roundedData.toString();
                return formattedData;
            }
            return data;
        };

        // Days columns
        const daysOfWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        const dayColumns = [
            // Loop through each day of the week
            ...daysOfWeek.map(day => ({
                title: day, // Use the day directly as the title
                data: day.toLowerCase(),
                ...commonColumnAttributes,
                render: roundedDataValue
            }))
        ];

        // Date columns
        const dateColumns = [{
                title: "{{ __('Start Date') }}",
                data: 'start_date',
                render: function(data) {
                    return moment(data).format('DD MMM YYYY');
                },
                ...commonColumnAttributes,
            },
            {
                title: "{{ __('End Date') }}",
                data: 'end_date',
                render: function(data) {
                    return moment(data).format('DD MMM YYYY');
                },
                ...commonColumnAttributes,
            },
            // {
            //     title: "{{ __('Total') }}",
            //     data: 'sum',
            //     ...commonColumnAttributes,
            //     render: roundedDataValue
            // },
            // {
            //     title: "{{ __('Avg') }}",
            //     data: 'avg',
            //     ...commonColumnAttributes,
            //     render: roundedDataValue
            // }
        ];

        // Actions columns
        const actionColumns = [{
            title: "{{ __('Action') }}",
            data: 'action',
            name: 'action',
            ...commonColumnAttributes
        }];

        // Initialize DataTable for table with id "history_weekly"
        $('#history_weekly').DataTable({
            ...dataTableConfig,
            ajax: {
                method: 'POST',
                url: "{{ url('forecast/history/data') . '/' . $params->id }}",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [
                // Column for "No."
                {
                    title: "No.",
                    data: "id",
                    class: 'text-center',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false,
                },
                ...dateColumns,
                ...dayColumns,
                ...actionColumns,
            ]
        });

        // Initialize DataTable for table with id "adjust_forecast"
        $('#adjust_forecast').DataTable({
            ...dataTableConfig,
            ajax: {
                method: 'POST',
                url: "{{ url('forecast/adjust/data') . '/' . $params->id }}",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [
                ...dayColumns,
                ...actionColumns,
            ]
        });

        // Initialize DataTable for table with id "co_forecast"
        $('#co_forecast').DataTable({
            ...dataTableConfig,
            ajax: {
                method: 'POST',
                url: "{{ url('forecast/history/average') . '/' . $params->id }}",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [
                ...dateColumns,
                ...dayColumns,
            ]
        });

        function calculateSum(row) {
            const reportingPeriod = {{ $params->reporting_period }};
            let sum = 0;

            for (const column of hoursColumns) {
                const value = parseInt(row[column.data], 10); // Parse the string value to an integer
                if (!isNaN(value)) {
                    sum += value;
                }
            }

            if (reportingPeriod === 3600) {
                return Math.round(sum / 8); // Round the result to the nearest whole number
            } else if (reportingPeriod === 1800) {
                return Math.round(sum / 16); // Round the result to the nearest whole number
            } else if (reportingPeriod === 900) {
                return Math.round(sum / 24); // Round the result to the nearest whole number
            } else {
                return 0; // Set a default value of 0 if the conditions don't match
            }
        }

        const dayTotalColumns = [{
                title: "{{ __('Day') }}",
                data: 'day',
                name: 'day',
                ...commonColumnAttributes,
            },
            {
                title: "Total",
                data: null,
                ...commonColumnAttributes,
                render: function(data, type, row) {
                    if (type === "display") {
                        // Display the calculated sum only for the "display" type
                        return calculateSum(row);
                    }
                    return data; // For other types, return the original data (to avoid any issues)
                },
            },
        ];

        const hoursColumns = [];

        for (let hour = 0; hour < 24; hour++) {
            const startTime = `${hour.toString().padStart(2, "0")}:00`;
            const nextHour = (hour + 1) % 24;
            const endTime = `${nextHour.toString().padStart(2, "0")}:00`;
            const title = `${startTime} - ${endTime}`;

            const column = {
                title: title,
                data: title,
                name: title,
                ...commonColumnAttributes
            };

            hoursColumns.push(column);
        }

        function initializeDataTable(elementId, urlPath) {
            $('#' + elementId).DataTable(
                Object.assign(dataTableConfig, {
                    columns: [
                        ...dayTotalColumns,
                        ...hoursColumns,
                    ],
                    ajax: {
                        method: 'POST',
                        url: "{{ url('forecast') }}/" + urlPath + "/{{ $params->id }}",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    },
                })
            );
        }

        initializeDataTable('fte_req_mon', 'mon');
        initializeDataTable('fte_req_tue', 'tue');
        initializeDataTable('fte_req_wed', 'wed');
        initializeDataTable('fte_req_thu', 'thu');
        initializeDataTable('fte_req_fri', 'fri');
        initializeDataTable('fte_req_sat', 'sat');
        initializeDataTable('fte_req_sun', 'sun');
    </script>
@endsection
