@extends('layouts.admin')

@section('title')
    {{ __('Weekly Forecast') }} | {{ config('app.name') }}
@endsection

@section('style')
    <link href="{{ asset(mix('css/datatable.css')) }}" rel="stylesheet">
@endsection

@section('subheader')
    {{ __('Forecast') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('weekly.index') }}"
        class="kt-subheader__breadcrumbs-link">{{ __('Weekly') }}</a>
@endsection

@section('content')
    <div class="kt-portlet">
        <div class="kt-portlet__body">
            <div class="kt-portlet__content">
                @include('layouts.inc.alert')
                <table class="table table-hover" id="kt_table_1"></table>
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
        $('#kt_table_1').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            language: {
                emptyTable: "{{ __('No data available in table') }}",
                info: "{{ __('Showing _START_ to _END_ of _sum_ entries') }}",
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
                url: '{{ route('weekly.data') }}',
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
                // {
                //     title: "{{ __('Week') }}",
                //     data: 'week',
                //     name: 'week',
                //     defaultContent: '-',
                //     class: 'text-center',
                // },
                {
                    title: "{{ __('Start Date') }}",
                    data: 'start_date',
                    name: 'start_date',
                    defaultContent: '-',
                    class: 'text-center',
                },
                {
                    title: "{{ __('End Date') }}",
                    data: 'end_date',
                    name: 'end_date',
                    defaultContent: '-',
                    class: 'text-center',
                },
                {
                    title: "{{ __('Mon') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_mon + " / " + row.pct_mon + "%";
                    }
                },
                {
                    title: "{{ __('Tue') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_tue + " / " + row.pct_tue + "%";
                    }
                },
                {
                    title: "{{ __('Wed') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_wed + " / " + row.pct_wed + "%";
                    }
                },
                {
                    title: "{{ __('Thu') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_thu + " / " + row.pct_thu + "%";
                    }
                },
                {
                    title: "{{ __('Fri') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_fri + " / " + row.pct_fri + "%";
                    }
                },
                {
                    title: "{{ __('Sat') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_sat + " / " + row.pct_sat + "%";
                    }
                },
                {
                    title: "{{ __('Sun') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_sun + " / " + row.pct_sun + "%";
                    }
                },
                // {
                //     title: "{{ __('Mon') }}",
                //     data: 'sum_mon',
                //     name: 'sum_mon',
                //     defaultContent: '-',
                //     class: 'text-center',
                //     searchable: false,
                //     orderable: false
                // },
                // {
                //     title: "{{ __('Tue') }}",
                //     data: 'sum_tue',
                //     name: 'sum_tue',
                //     defaultContent: '-',
                //     class: 'text-center',
                //     searchable: false,
                //     orderable: false
                // },
                // {
                //     title: "{{ __('Wed') }}",
                //     data: 'sum_wed',
                //     name: 'sum_wed',
                //     defaultContent: '-',
                //     class: 'text-center',
                //     searchable: false,
                //     orderable: false
                // },
                // {
                //     title: "{{ __('Thu') }}",
                //     data: 'sum_thu',
                //     name: 'sum_thu',
                //     defaultContent: '-',
                //     class: 'text-center',
                //     searchable: false,
                //     orderable: false
                // },
                // {
                //     title: "{{ __('Fri') }}",
                //     data: 'sum_fri',
                //     name: 'sum_fri',
                //     defaultContent: '-',
                //     class: 'text-center',
                //     searchable: false,
                //     orderable: false
                // },
                // {
                //     title: "{{ __('Sat') }}",
                //     data: 'sum_sat',
                //     name: 'sum_sat',
                //     defaultContent: '-',
                //     class: 'text-center',
                //     searchable: false,
                //     orderable: false
                // },
                // {
                //     title: "{{ __('Sun') }}",
                //     data: 'sum_sun',
                //     name: 'sum_sun',
                //     defaultContent: '-',
                //     class: 'text-center',
                //     searchable: false,
                //     orderable: false
                // },
                {
                    title: "{{ __('Total Calls / Week') }}",
                    data: 'sum_per_week',
                    name: 'sum_per_week',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "{{ __('Avg Calls / Day') }}",
                    data: 'avg_per_day',
                    name: 'avg_per_day',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },

            ],
            drawCallback: function() {
                $('.btn-tooltip').tooltip();
            }
        });
    </script>
@endsection
