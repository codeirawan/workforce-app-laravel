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

        const patternWeeklyCard = generateCard('Pattern Weekly', 'fa-clock-rotate-left');
        const coForecastCard = generateCard('CO Forecast', 'fa-chart-simple');
        document.getElementById('cards-container').innerHTML = patternWeeklyCard + coForecastCard;
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

        // Common columns for both tables
        const daysOfWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        const commonColumns = [{
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
            // Loop through each day of the week
            ...daysOfWeek.map(day => ({
                title: day, // Use the day directly as the title
                data: day.toLowerCase(),
                ...commonColumnAttributes,
                render: roundedDataValue
            })),
            {
                title: "{{ __('Total') }}",
                data: 'sum',
                ...commonColumnAttributes,
                render: roundedDataValue
            },
            {
                title: "{{ __('Avg') }}",
                data: 'avg',
                ...commonColumnAttributes,
                render: roundedDataValue
            }
        ];

        // Initialize DataTable for table with id "pattern_weekly"
        $('#pattern_weekly').DataTable({
            ...dataTableConfig,
            ajax: {
                method: 'POST',
                url: "{{ url('forecast/history') . '/' . $params->id }}",
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
                // Common columns
                ...commonColumns,
                // Specific column for "pattern_weekly" table
                {
                    title: "{{ __('Action') }}",
                    data: 'action',
                    name: 'action',
                    ...commonColumnAttributes
                }
            ]
        });

        // Initialize DataTable for table with id "co_forecast"
        $('#co_forecast').DataTable({
            ...dataTableConfig,
            ajax: {
                method: 'POST',
                url: "{{ url('forecast/average') . '/' . $params->id }}",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [
                // Common columns
                ...commonColumns,
                // Specific column for "co_forecast" table
                // (No "Action" column for "co_forecast" table)
            ]
        });
    </script>

    <script type="text/javascript">
        function calculateSum(row) {
            const reportingPeriod = {{ $params->reporting_period }};
            let sum = 0;

            for (const column of dataTableColumns) {
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

        const dataTableColumns = [{
                title: "{{ __('Day') }}",
                data: 'day',
                name: 'day',
                defaultContent: '-',
                class: 'text-center',
                searchable: false,
                orderable: false
            },
            {
                title: "Total",
                data: null,
                defaultContent: '-',
                class: 'text-center',
                searchable: false,
                orderable: false,
                render: function(data, type, row) {
                    return calculateSum(row);
                },
            },
            {
                title: "00:00 - 01:00",
                data: '00:00 - 01:00',
                name: '00:00 - 01:00',
                defaultContent: '-',
                class: 'text-center',
                searchable: false,
                orderable: false
            },
            {
                title: "01:00 - 02:00",
                data: '01:00 - 02:00',
                name: '01:00 - 02:00',
                defaultContent: '-',
                class: 'text-center',
                searchable: false,
                orderable: false
            },
            {
                title: "02:00 - 03:00",
                data: '02:00 - 03:00',
                name: '02:00 - 03:00',
                defaultContent: '-',
                class: 'text-center',
                searchable: false,
                orderable: false
            },
            {
                title: "03:00 - 04:00",
                data: '03:00 - 04:00',
                name: '03:00 - 04:00',
                defaultContent: '-',
                class: 'text-center',
                searchable: false,
                orderable: false
            },
            {
                title: "04:00 - 05:00",
                data: '04:00 - 05:00',
                name: '04:00 - 05:00',
                defaultContent: '-',
                class: 'text-center',
                searchable: false,
                orderable: false
            },
            {
                title: "05:00 - 06:00",
                data: '05:00 - 06:00',
                name: '05:00 - 06:00',
                defaultContent: '-',
                class: 'text-center',
                searchable: false,
                orderable: false
            },
            {
                title: "06:00 - 07:00",
                data: '06:00 - 07:00',
                name: '06:00 - 07:00',
                defaultContent: '-',
                class: 'text-center',
                searchable: false,
                orderable: false
            },
            {
                title: "07:00 - 08:00",
                data: '07:00 - 08:00',
                name: '07:00 - 08:00',
                defaultContent: '-',
                class: 'text-center',
                searchable: false,
                orderable: false
            },
            {
                title: "08:00 - 09:00",
                data: '08:00 - 09:00',
                name: '08:00 - 09:00',
                defaultContent: '-',
                class: 'text-center',
                searchable: false,
                orderable: false
            },
            {
                title: "09:00 - 10:00",
                data: '09:00 - 10:00',
                name: '09:00 - 10:00',
                defaultContent: '-',
                class: 'text-center',
                searchable: false,
                orderable: false
            },
            {
                title: "10:00 - 11:00",
                data: '10:00 - 11:00',
                name: '10:00 - 11:00',
                defaultContent: '-',
                class: 'text-center',
                searchable: false,
                orderable: false
            },
            {
                title: "11:00 - 12:00",
                data: '11:00 - 12:00',
                name: '11:00 - 12:00',
                defaultContent: '-',
                class: 'text-center',
                searchable: false,
                orderable: false
            },
            {
                title: "12:00 - 13:00",
                data: '12:00 - 13:00',
                name: '12:00 - 13:00',
                defaultContent: '-',
                class: 'text-center',
                searchable: false,
                orderable: false
            },
            {
                title: "13:00 - 14:00",
                data: '13:00 - 14:00',
                name: '13:00 - 14:00',
                defaultContent: '-',
                class: 'text-center',
                searchable: false,
                orderable: false
            },
            {
                title: "14:00 - 15:00",
                data: '14:00 - 15:00',
                name: '14:00 - 15:00',
                defaultContent: '-',
                class: 'text-center',
                searchable: false,
                orderable: false
            },
            {
                title: "15:00 - 16:00",
                data: '15:00 - 16:00',
                name: '15:00 - 16:00',
                defaultContent: '-',
                class: 'text-center',
                searchable: false,
                orderable: false
            },
            {
                title: "16:00 - 17:00",
                data: '16:00 - 17:00',
                name: '16:00 - 17:00',
                defaultContent: '-',
                class: 'text-center',
                searchable: false,
                orderable: false
            },
            {
                title: "17:00 - 18:00",
                data: '17:00 - 18:00',
                name: '17:00 - 18:00',
                defaultContent: '-',
                class: 'text-center',
                searchable: false,
                orderable: false
            },
            {
                title: "18:00 - 19:00",
                data: '18:00 - 19:00',
                name: '18:00 - 19:00',
                defaultContent: '-',
                class: 'text-center',
                searchable: false,
                orderable: false
            },
            {
                title: "19:00 - 20:00",
                data: '19:00 - 20:00',
                name: '19:00 - 20:00',
                defaultContent: '-',
                class: 'text-center',
                searchable: false,
                orderable: false
            },
            {
                title: "20:00 - 21:00",
                data: '20:00 - 21:00',
                name: '20:00 - 21:00',
                defaultContent: '-',
                class: 'text-center',
                searchable: false,
                orderable: false
            },
            {
                title: "21:00 - 22:00",
                data: '21:00 - 22:00',
                name: '21:00 - 22:00',
                defaultContent: '-',
                class: 'text-center',
                searchable: false,
                orderable: false
            },
            {
                title: "22:00 - 23:00",
                data: '22:00 - 23:00',
                name: '22:00 - 23:00',
                defaultContent: '-',
                class: 'text-center',
                searchable: false,
                orderable: false
            },
            {
                title: "23:00 - 00:00",
                data: '23:00 - 00:00',
                name: '23:00 - 00:00',
                defaultContent: '-',
                class: 'text-center',
                searchable: false,
                orderable: false
            }
        ];

        const processingConfig = {
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
            drawCallback: function() {
                $('.btn-tooltip').tooltip();
            },
            initComplete: function() {
                $('.dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate').hide();
            }
        };

        function initializeDataTable(elementId, urlPath) {
            $('#' + elementId).DataTable(
                Object.assign(processingConfig, {
                    columns: dataTableColumns,
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
