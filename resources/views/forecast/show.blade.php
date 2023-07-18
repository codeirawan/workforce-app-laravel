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



                <div class="card card-custom gutter-b mb-4">
                    <div class="bg-secondary text-dark">
                        <div class="card-title">
                            <h6 class="card-label mt-2 ml-4 p-2">
                                Pattern Weekly by History Actual <i class="fa-solid fa-clock-rotate-left"></i>
                            </h6>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered" id="kt_table_1"></table>
                    </div>
                </div>

                <div class="card card-custom gutter-b mb-4">
                    <div class="bg-secondary text-dark">
                        <div class="card-title">
                            <h6 class="card-label mt-2 ml-4 p-2">
                                CO Forecast <i class="fa-solid fa-chart-simple"></i>
                            </h6>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered" id="kt_table_2"></table>
                    </div>
                </div>

                <div class="card card-custom gutter-b mb-4">
                    <div class="bg-secondary text-dark">
                        <div class="card-title">
                            <h6 class="card-label mt-2 ml-4 p-2">
                                FTE Requirement Daily | Params =
                                AHT : {{ $params->avg_handling_time }}s,
                                Reporting Period : {{ $params->reporting_period }}s,
                                SL : {{ $params->service_level }}%,
                                Target Answer Time : {{ $params->target_answer_time }}s,
                                Shrinkage : {{ $params->shrinkage }}%

                                <a href="{{ route('forecast.edit', $params->id) }}" title="{{ Lang::get('Edit') }}">
                                    <i class="fa-solid fa-edit"></i>
                                </a>
                            </h6>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered" id="kt_table_3"></table>
                    </div>
                </div>

                <div class="card card-custom gutter-b mb-4">
                    <div class="bg-secondary text-dark">
                        <div class="card-title">
                            <h6 class="card-label mt-2 ml-4 p-2">
                                FTE Interval Monday
                            </h6>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered" id="fte_req_mon"></table>
                    </div>
                </div>

                <div class="card card-custom gutter-b mb-4">
                    <div class="bg-secondary text-dark">
                        <div class="card-title">
                            <h6 class="card-label mt-2 ml-4 p-2">
                                FTE Interval Tuesday
                            </h6>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered" id="fte_req_tue"></table>
                    </div>
                </div>

                <div class="card card-custom gutter-b mb-4">
                    <div class="bg-secondary text-dark">
                        <div class="card-title">
                            <h6 class="card-label mt-2 ml-4 p-2">
                                FTE Interval Wednesday
                            </h6>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered" id="fte_req_wed"></table>
                    </div>
                </div>

                <div class="card card-custom gutter-b mb-4">
                    <div class="bg-secondary text-dark">
                        <div class="card-title">
                            <h6 class="card-label mt-2 ml-4 p-2">
                                FTE Interval Thursday
                            </h6>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered" id="fte_req_thu"></table>
                    </div>
                </div>

                <div class="card card-custom gutter-b mb-4">
                    <div class="bg-secondary text-dark">
                        <div class="card-title">
                            <h6 class="card-label mt-2 ml-4 p-2">
                                FTE Interval Friday
                            </h6>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered" id="fte_req_fri"></table>
                    </div>
                </div>

                <div class="card card-custom gutter-b mb-4">
                    <div class="bg-secondary text-dark">
                        <div class="card-title">
                            <h6 class="card-label mt-2 ml-4 p-2">
                                FTE Interval Saturday
                            </h6>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered" id="fte_req_sat"></table>
                    </div>
                </div>

                <div class="card card-custom gutter-b mb-4">
                    <div class="bg-secondary text-dark">
                        <div class="card-title">
                            <h6 class="card-label mt-2 ml-4 p-2">
                                FTE Interval Sunday
                            </h6>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered" id="fte_req_sun"></table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('forecast.modal.add-history')

    <script src="{{ asset(mix('js/datatable.js')) }}"></script>
    <script src="{{ asset(mix('js/tooltip.js')) }}"></script>
    <script src="{{ asset(mix('js/form/validation.js')) }}"></script>

    <script type="text/javascript">
        $('#kt_table_1').DataTable({
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
            ajax: {
                method: 'POST',
                url: "{{ url('forecast/history') . '/' . $params->id }}",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [{
                    title: "No.",
                    data: "id",
                    class: 'text-center',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false,
                },
                {
                    title: "{{ __('Start Date') }}",
                    data: 'start_date',
                    name: 'start_date',
                    defaultContent: '-',
                    class: 'text-center',
                    render: function(data) {
                        return moment(data).format('DD MMM YYYY');
                    },
                    searchable: false,
                    orderable: false
                },
                {
                    title: "{{ __('End Date') }}",
                    data: 'end_date',
                    name: 'end_date',
                    defaultContent: '-',
                    class: 'text-center',
                    render: function(data) {
                        return moment(data).format('DD MMM YYYY');
                    },
                    searchable: false,
                    orderable: false
                },
                {
                    title: "{{ __('Mon') }}",
                    data: 'mon',
                    name: 'mon',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "{{ __('Tue') }}",
                    data: 'tue',
                    name: 'tue',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "{{ __('Wed') }}",
                    data: 'wed',
                    name: 'wed',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "{{ __('Thu') }}",
                    data: 'thu',
                    name: 'thu',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "{{ __('Fri') }}",
                    data: 'fri',
                    name: 'fri',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "{{ __('Sat') }}",
                    data: 'sat',
                    name: 'sat',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "{{ __('Sun') }}",
                    data: 'sun',
                    name: 'sun',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "{{ __('Total') }}",
                    data: 'sum',
                    name: 'sum',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "{{ __('Avg') }}",
                    data: 'avg',
                    name: 'avg',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        if (type === 'display' && data !== '-') {
                            var roundedData = Math.round(data);
                            var formattedData = roundedData.toString();
                            return formattedData;
                        }
                        return data;
                    }
                },
                // {
                //     title: "{{ __('Action') }}",
                //     data: 'action',
                //     name: 'action',
                //     defaultContent: '-',
                //     class: 'text-center',
                //     searchable: false,
                //     orderable: false
                // }
            ],
            order: [
                [1, 'desc']
            ],
            drawCallback: function() {
                $('.btn-tooltip').tooltip();
            }
        });
    </script>

    <script type="text/javascript">
        $('#kt_table_2').DataTable({
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
            ajax: {
                method: 'POST',
                url: "{{ url('forecast/average') . '/' . $params->id }}",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [{
                    title: "{{ __('Start Date') }}",
                    data: 'start_date',
                    name: 'start_date',
                    defaultContent: '-',
                    class: 'text-center',
                    render: function(data) {
                        return moment(data).format('DD MMM YYYY');
                    },
                    searchable: false,
                    orderable: false
                },
                {
                    title: "{{ __('End Date') }}",
                    data: 'end_date',
                    name: 'end_date',
                    defaultContent: '-',
                    class: 'text-center',
                    render: function(data) {
                        return moment(data).format('DD MMM YYYY');
                    },
                    searchable: false,
                    orderable: false
                },
                {
                    title: "{{ __('Mon') }}",
                    data: 'mon',
                    name: 'mon',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        if (type === 'display' && data !== '-') {
                            var roundedData = Math.round(data);
                            var formattedData = roundedData.toString();
                            return formattedData;
                        }
                        return data;
                    }
                },
                {
                    title: "{{ __('Tue') }}",
                    data: 'tue',
                    name: 'tue',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        if (type === 'display' && data !== '-') {
                            var roundedData = Math.round(data);
                            var formattedData = roundedData.toString();
                            return formattedData;
                        }
                        return data;
                    }
                },
                {
                    title: "{{ __('Wed') }}",
                    data: 'wed',
                    name: 'wed',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        if (type === 'display' && data !== '-') {
                            var roundedData = Math.round(data);
                            var formattedData = roundedData.toString();
                            return formattedData;
                        }
                        return data;
                    }
                },
                {
                    title: "{{ __('Thu') }}",
                    data: 'thu',
                    name: 'thu',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        if (type === 'display' && data !== '-') {
                            var roundedData = Math.round(data);
                            var formattedData = roundedData.toString();
                            return formattedData;
                        }
                        return data;
                    }
                },
                {
                    title: "{{ __('Fri') }}",
                    data: 'fri',
                    name: 'fri',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        if (type === 'display' && data !== '-') {
                            var roundedData = Math.round(data);
                            var formattedData = roundedData.toString();
                            return formattedData;
                        }
                        return data;
                    }
                },
                {
                    title: "{{ __('Sat') }}",
                    data: 'sat',
                    name: 'sat',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        if (type === 'display' && data !== '-') {
                            var roundedData = Math.round(data);
                            var formattedData = roundedData.toString();
                            return formattedData;
                        }
                        return data;
                    }
                },
                {
                    title: "{{ __('Sun') }}",
                    data: 'sun',
                    name: 'sun',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        if (type === 'display' && data !== '-') {
                            var roundedData = Math.round(data);
                            var formattedData = roundedData.toString();
                            return formattedData;
                        }
                        return data;
                    }
                },
                {
                    title: "{{ __('Total') }}",
                    data: 'sum',
                    name: 'sum',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        if (type === 'display' && data !== '-') {
                            var roundedData = Math.round(data);
                            var formattedData = roundedData.toString();
                            return formattedData;
                        }
                        return data;
                    }
                },
                {
                    title: "{{ __('Avg') }}",
                    data: 'avg',
                    name: 'avg',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        if (type === 'display' && data !== '-') {
                            var roundedData = Math.round(data);
                            var formattedData = roundedData.toString();
                            return formattedData;
                        }
                        return data;
                    }
                }
            ],
            order: [
                [1, 'desc']
            ],
            drawCallback: function() {
                $('.btn-tooltip').tooltip();
            }
        });
    </script>

    <script type="text/javascript">
        $('#kt_table_3').DataTable({
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
            ajax: {
                method: 'POST',
                url: "{{ url('forecast/average') . '/' . $params->id }}",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [{
                    title: "{{ __('Start Date') }}",
                    data: 'start_date',
                    name: 'start_date',
                    defaultContent: '-',
                    class: 'text-center',
                    render: function(data) {
                        return moment(data).format('DD MMM YYYY');
                    },
                    searchable: false,
                    orderable: false
                },
                {
                    title: "{{ __('End Date') }}",
                    data: 'end_date',
                    name: 'end_date',
                    defaultContent: '-',
                    class: 'text-center',
                    render: function(data) {
                        return moment(data).format('DD MMM YYYY');
                    },
                    searchable: false,
                    orderable: false
                },
                {
                    title: "{{ __('Mon') }}",
                    data: 'mon',
                    name: 'mon',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data) {
                        var reportingPeriod =
                            {{ $params->reporting_period }}; // Assigning the value of $params->reporting_period to a variable
                        var result;

                        if (reportingPeriod === 3600) {
                            result = data / 8;
                        } else if (reportingPeriod === 1800) {
                            result = data / 16;
                        } else if (reportingPeriod === 900) {
                            result = data / 24;
                        } else {
                            result = 0; // Set a default value if the conditions don't match
                        }

                        return Math.round(result);
                    }
                },
                {
                    title: "{{ __('Tue') }}",
                    data: 'tue',
                    name: 'tue',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data) {
                        var reportingPeriod =
                            {{ $params->reporting_period }}; // Assigning the value of $params->reporting_period to a variable
                        var result;

                        if (reportingPeriod === 3600) {
                            result = data / 8;
                        } else if (reportingPeriod === 1800) {
                            result = data / 16;
                        } else if (reportingPeriod === 900) {
                            result = data / 24;
                        } else {
                            result = 0; // Set a default value if the conditions don't match
                        }

                        return Math.round(result);
                    }
                },
                {
                    title: "{{ __('Wed') }}",
                    data: 'wed',
                    name: 'wed',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data) {
                        var reportingPeriod =
                            {{ $params->reporting_period }}; // Assigning the value of $params->reporting_period to a variable
                        var result;

                        if (reportingPeriod === 3600) {
                            result = data / 8;
                        } else if (reportingPeriod === 1800) {
                            result = data / 16;
                        } else if (reportingPeriod === 900) {
                            result = data / 24;
                        } else {
                            result = 0; // Set a default value if the conditions don't match
                        }

                        return Math.round(result);
                    }
                },
                {
                    title: "{{ __('Thu') }}",
                    data: 'thu',
                    name: 'thu',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data) {
                        var reportingPeriod =
                            {{ $params->reporting_period }}; // Assigning the value of $params->reporting_period to a variable
                        var result;

                        if (reportingPeriod === 3600) {
                            result = data / 8;
                        } else if (reportingPeriod === 1800) {
                            result = data / 16;
                        } else if (reportingPeriod === 900) {
                            result = data / 24;
                        } else {
                            result = 0; // Set a default value if the conditions don't match
                        }

                        return Math.round(result);
                    }
                },
                {
                    title: "{{ __('Fri') }}",
                    data: 'fri',
                    name: 'fri',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data) {
                        var reportingPeriod =
                            {{ $params->reporting_period }}; // Assigning the value of $params->reporting_period to a variable
                        var result;

                        if (reportingPeriod === 3600) {
                            result = data / 8;
                        } else if (reportingPeriod === 1800) {
                            result = data / 16;
                        } else if (reportingPeriod === 900) {
                            result = data / 24;
                        } else {
                            result = 0; // Set a default value if the conditions don't match
                        }

                        return Math.round(result);
                    }
                },
                {
                    title: "{{ __('Sat') }}",
                    data: 'sat',
                    name: 'sat',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data) {
                        var reportingPeriod =
                            {{ $params->reporting_period }}; // Assigning the value of $params->reporting_period to a variable
                        var result;

                        if (reportingPeriod === 3600) {
                            result = data / 8;
                        } else if (reportingPeriod === 1800) {
                            result = data / 16;
                        } else if (reportingPeriod === 900) {
                            result = data / 24;
                        } else {
                            result = 0; // Set a default value if the conditions don't match
                        }

                        return Math.round(result);
                    }
                },
                {
                    title: "{{ __('Sun') }}",
                    data: 'sun',
                    name: 'sun',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data) {
                        var reportingPeriod =
                            {{ $params->reporting_period }}; // Assigning the value of $params->reporting_period to a variable
                        var result;

                        if (reportingPeriod === 3600) {
                            result = data / 8;
                        } else if (reportingPeriod === 1800) {
                            result = data / 16;
                        } else if (reportingPeriod === 900) {
                            result = data / 24;
                        } else {
                            result = 0; // Set a default value if the conditions don't match
                        }

                        return Math.round(result);
                    }
                },
                {
                    title: "{{ __('Total') }}",
                    data: 'sum',
                    name: 'sum',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data) {
                        var reportingPeriod =
                            {{ $params->reporting_period }}; // Assigning the value of $params->reporting_period to a variable
                        var result;

                        if (reportingPeriod === 3600) {
                            result = data / 8;
                        } else if (reportingPeriod === 1800) {
                            result = data / 16;
                        } else if (reportingPeriod === 900) {
                            result = data / 24;
                        } else {
                            result = 0; // Set a default value if the conditions don't match
                        }

                        return Math.round(result);
                    }
                },
                {
                    title: "{{ __('Avg') }}",
                    data: 'avg',
                    name: 'avg',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data) {
                        var reportingPeriod =
                            {{ $params->reporting_period }}; // Assigning the value of $params->reporting_period to a variable
                        var result;

                        if (reportingPeriod === 3600) {
                            result = data / 8;
                        } else if (reportingPeriod === 1800) {
                            result = data / 16;
                        } else if (reportingPeriod === 900) {
                            result = data / 24;
                        } else {
                            result = 0; // Set a default value if the conditions don't match
                        }

                        return Math.round(result);
                    }
                }
            ],
            order: [
                [1, 'desc']
            ],
            drawCallback: function() {
                $('.btn-tooltip').tooltip();
            }
        });
    </script>

    <script type="text/javascript">
        $('#fte_req_mon').DataTable({
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
            ajax: {
                method: 'POST',
                url: "{{ url('forecast/mon') . '/' . $params->id }}",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [{
                    title: "{{ __('Day') }}",
                    data: 'day',
                    name: 'day',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
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
            ],
            drawCallback: function() {
                $('.btn-tooltip').tooltip();
            }
        }).on('draw.dt', function() {
            $('.dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate')
                .hide();
        });
    </script>

    <script type="text/javascript">
        $('#fte_req_tue').DataTable({
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
            ajax: {
                method: 'POST',
                url: "{{ url('forecast/tue') . '/' . $params->id }}",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [{
                    title: "{{ __('Day') }}",
                    data: 'day',
                    name: 'day',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
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
            ],
            drawCallback: function() {
                $('.btn-tooltip').tooltip();
            }
        }).on('draw.dt', function() {
            $('.dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate').hide();
        });
    </script>

    <script type="text/javascript">
        $('#fte_req_wed').DataTable({
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
            ajax: {
                method: 'POST',
                url: "{{ url('forecast/wed') . '/' . $params->id }}",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [{
                    title: "{{ __('Day') }}",
                    data: 'day',
                    name: 'day',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
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
            ],
            drawCallback: function() {
                $('.btn-tooltip').tooltip();
            }
        }).on('draw.dt', function() {
            $('.dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate').hide();
        });
    </script>

    <script type="text/javascript">
        $('#fte_req_thu').DataTable({
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
            ajax: {
                method: 'POST',
                url: "{{ url('forecast/thu') . '/' . $params->id }}",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [{
                    title: "{{ __('Day') }}",
                    data: 'day',
                    name: 'day',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
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
            ],
            drawCallback: function() {
                $('.btn-tooltip').tooltip();
            }
        }).on('draw.dt', function() {
            $('.dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate').hide();
        });
    </script>

    <script type="text/javascript">
        $('#fte_req_fri').DataTable({
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
            ajax: {
                method: 'POST',
                url: "{{ url('forecast/fri') . '/' . $params->id }}",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [{
                    title: "{{ __('Day') }}",
                    data: 'day',
                    name: 'day',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
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
            ],
            drawCallback: function() {
                $('.btn-tooltip').tooltip();
            }
        }).on('draw.dt', function() {
            $('.dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate').hide();
        });
    </script>

    <script type="text/javascript">
        $('#fte_req_sat').DataTable({
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
            ajax: {
                method: 'POST',
                url: "{{ url('forecast/sat') . '/' . $params->id }}",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [{
                    title: "{{ __('Day') }}",
                    data: 'day',
                    name: 'day',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
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
            ],
            drawCallback: function() {
                $('.btn-tooltip').tooltip();
            }
        }).on('draw.dt', function() {
            $('.dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate').hide();
        });
    </script>

    <script type="text/javascript">
        $('#fte_req_sun').DataTable({
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
            ajax: {
                method: 'POST',
                url: "{{ url('forecast/sun') . '/' . $params->id }}",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [{
                    title: "{{ __('Day') }}",
                    data: 'day',
                    name: 'day',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
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
            ],
            drawCallback: function() {
                $('.btn-tooltip').tooltip();
            }
        }).on('draw.dt', function() {
            $('.dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate').hide();
        });
    </script>
@endsection
