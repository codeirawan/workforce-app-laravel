@extends('layouts.admin')

@section('title')
    {{ __('Monthly Forecast') }} | {{ config('app.name') }}
@endsection

@section('style')
    <link href="{{ asset(mix('css/datatable.css')) }}" rel="stylesheet">
@endsection

@section('subheader')
    {{ __('Forecast') }}
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
                        return row.sum_jan + "  -  " + row.pct_jan + "%";
                    }
                },
                {
                    title: "{{ __('Feb') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_feb + "  -  " + row.pct_feb + "%";
                    }
                },
                {
                    title: "{{ __('Mar') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_mar + "  -  " + row.pct_mar + "%";
                    }
                },
                {
                    title: "{{ __('Apr') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_apr + "  -  " + row.pct_apr + "%";
                    }
                },
                {
                    title: "{{ __('May') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_may + "  -  " + row.pct_may + "%";
                    }
                },
                {
                    title: "{{ __('Jun') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_jun + "  -  " + row.pct_jun + "%";
                    }
                },
                {
                    title: "{{ __('Jul') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_jul + "  -  " + row.pct_jul + "%";
                    }
                },
                {
                    title: "{{ __('Aug') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_aug + "  -  " + row.pct_aug + "%";
                    }
                },
                {
                    title: "{{ __('Sep') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_sep + "  -  " + row.pct_sep + "%";
                    }
                },
                {
                    title: "{{ __('Oct') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_oct + "  -  " + row.pct_oct + "%";
                    }
                },
                {
                    title: "{{ __('Nov') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_nov + "  -  " + row.pct_nov + "%";
                    }
                },
                {
                    title: "{{ __('Dec') }}",
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return row.sum_dec + "  -  " + row.pct_dec + "%";
                    }
                },
                {
                    title: "{{ __('Total Calls / Year') }}",
                    data: 'sum_per_year',
                    name: 'sum_per_year',
                    defaultContent: '-',
                    class: 'text-center',
                },
                {
                    title: "{{ __('Avg Calls / Month') }}",
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
@endsection
