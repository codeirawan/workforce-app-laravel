@extends('layouts.app')

@section('title')
    {{ __('Monthly Volume') }} | {{ config('app.name') }}
@endsection

@section('style')
    <link href="{{ asset(mix('css/datatable.css')) }}" rel="stylesheet">
@endsection

@section('subheader')
    {{ __('History') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('monthly.index') }}"
        class="kt-subheader__breadcrumbs-link">{{ __('Monthly') }}</a>
@endsection

@section('content')
    <div class="kt-portlet">
        <div class="kt-portlet__body">
            <div class="kt-portlet__content">
                @include('layouts.inc.alert')
                <div class="card card-custom gutter-b mb-4">
                    <div class="bg-secondary text-dark">
                        <div class="card-title">
                            <h5 class="card-label mt-2 ml-4">
                                Volume
                            </h5>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-hover" id="data"></table>
                    </div>
                </div>
                {{-- <div class="card card-custom gutter-b">
                    <div class="bg-secondary text-dark">
                        <div class="card-title">
                            <h5 class="card-label mt-2 ml-4">
                                Distribution
                            </h5>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-hover" id="distribution"></table>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('layouts.inc.modal.delete', ['object' => 'data'])

    <script src="{{ asset(mix('js/datatable.js')) }}"></script>
    <script src="{{ asset(mix('js/tooltip.js')) }}"></script>
    <script src="{{ asset(mix('js/form/validation.js')) }}"></script>
    <script type="text/javascript">
        $('#data').DataTable({
            processing: true,
            serverSide: true,
            language: {
                emptyTable: "{{ __('No data available in table') }}",
                info: "{{ __('Showing _START_ to _END_ of _TOTAL_ entries') }}",
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
                url: '{{ route('monthly.data') }}',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [{
                    title: "No.",
                    data: "id",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false,
                },
                {
                    title: "{{ __('Year') }}",
                    data: 'year',
                    name: 'year',
                    defaultContent: '-',
                    class: 'text-center',
                },
                {
                    title: "{{ __('Jan') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_jan;
                    }
                },
                {
                    title: "{{ __('Feb') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_feb;
                    }
                },
                {
                    title: "{{ __('Mar') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_mar;
                    }
                },
                {
                    title: "{{ __('Apr') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_apr;
                    }
                },
                {
                    title: "{{ __('May') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_may;
                    }
                },
                {
                    title: "{{ __('Jun') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_jun;
                    }
                },
                {
                    title: "{{ __('Jul') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_jul;
                    }
                },
                {
                    title: "{{ __('Aug') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_aug;
                    }
                },
                {
                    title: "{{ __('Sep') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_sep;
                    }
                },
                {
                    title: "{{ __('Oct') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_oct;
                    }
                },
                {
                    title: "{{ __('Nov') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_nov;
                    }
                },
                {
                    title: "{{ __('Dec') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_dec;
                    }
                },
                {
                    title: "{{ __('Total / Year') }}",
                    data: 'sum_per_year',
                    name: 'sum_per_year',
                    defaultContent: '-',
                    class: 'text-center',
                },
                {
                    title: "{{ __('Avg / Month') }}",
                    data: 'avg_per_month',
                    name: 'avg_per_month',
                    defaultContent: '-',
                    class: 'text-center',
                }

            ],
            drawCallback: function() {
                $('.btn-tooltip').tooltip();
            }
        });
    </script>
    <script type="text/javascript">
        $('#distribution').DataTable({
            processing: true,
            serverSide: true,
            language: {
                emptyTable: "{{ __('No data available in table') }}",
                info: "{{ __('Showing _START_ to _END_ of _TOTAL_ entries') }}",
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
                url: '{{ route('monthly.distribution') }}',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [{
                    title: "No.",
                    data: "id",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false,
                },
                {
                    title: "{{ __('Year') }}",
                    data: 'year',
                    name: 'year',
                    defaultContent: '-',
                    class: 'text-center',
                },
                {
                    title: "{{ __('Jan') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.pct_jan + "%";
                    }
                },
                {
                    title: "{{ __('Feb') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.pct_feb + "%";
                    }
                },
                {
                    title: "{{ __('Mar') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.pct_mar + "%";
                    }
                },
                {
                    title: "{{ __('Apr') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.pct_apr + "%";
                    }
                },
                {
                    title: "{{ __('May') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.pct_may + "%";
                    }
                },
                {
                    title: "{{ __('Jun') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.pct_jun + "%";
                    }
                },
                {
                    title: "{{ __('Jul') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.pct_jul + "%";
                    }
                },
                {
                    title: "{{ __('Aug') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.pct_aug + "%";
                    }
                },
                {
                    title: "{{ __('Sep') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.pct_sep + "%";
                    }
                },
                {
                    title: "{{ __('Oct') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.pct_oct + "%";
                    }
                },
                {
                    title: "{{ __('Nov') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.pct_nov + "%";
                    }
                },
                {
                    title: "{{ __('Dec') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.pct_dec + "%";
                    }
                },
                // {
                //     title: "{{ __('Total / Year') }}",
                //     data: 'sum_per_year',
                //     name: 'sum_per_year',
                //     defaultContent: '-',
                //     class: 'text-center',
                // },
                // {
                //     title: "{{ __('Avg / Month') }}",
                //     data: 'avg_per_month',
                //     name: 'avg_per_month',
                //     defaultContent: '-',
                //     class: 'text-center',
                // }
            ],
            drawCallback: function() {
                $('.btn-tooltip').tooltip();
            }
        });
    </script>
@endsection
