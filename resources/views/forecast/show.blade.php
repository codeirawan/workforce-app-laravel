@extends('layouts.app')

@section('title')
    {{ __('Calculation Forecast') }} | {{ config('app.name') }}
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
    <a href="{{ route('forecast.index') }}" class="kt-subheader__breadcrumbs-link">{{ __('Calculation Forecast') }}
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
                                FTE Requirement | Params =
                                AHT : {{ $params->avg_handling_time }}s |
                                Reporting Period : {{ $params->reporting_period }}s |
                                SL : {{ $params->service_level }}% |
                                Target Answer Time : {{ $params->target_answer_time }}s |
                                Shrinkage : {{ $params->shrinkage }}%

                                <a href="{{ route('forecast.edit', $params->id) }}" title="{{ Lang::get('Edit') }}"> <i
                                        class="fa-solid fa-edit"></i>
                            </h6>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered" id="kt_table_3"></table>
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
                url: "{{ url('forecast/result') . '/' . $params->id }}",
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
                        return Math.round(data);
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
                        return Math.round(data);
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
                        return Math.round(data);
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
                        return Math.round(data);
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
                        return Math.round(data);
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
                        return Math.round(data);
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
                        return Math.round(data);
                    }
                },
                {
                    title: "{{ __('Total') }}",
                    data: 'total_sum',
                    name: 'total_sum',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data) {
                        return Math.round(data);
                    }
                },
                {
                    title: "{{ __('Avg') }}",
                    data: 'total_avg',
                    name: 'total_avg',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data) {
                        return Math.round(data);
                    }
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
