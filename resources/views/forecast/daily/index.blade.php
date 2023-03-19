@extends('layouts.admin')

@section('title')
    {{ __('Daily Forecast') }} | {{ config('app.name') }}
@endsection

@section('style')
    <link href="{{ asset(mix('css/datatable.css')) }}" rel="stylesheet">
@endsection

@section('subheader')
    {{ __('Forecast') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('daily.index') }}"
        class="kt-subheader__breadcrumbs-link">{{ __('Daily') }}</a>
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
                url: '{{ route('daily.data') }}',
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
                    title: "{{ __('Day') }}",
                    data: 'day',
                    name: 'day',
                    defaultContent: '-',
                    class: 'text-center',
                },
                {
                    title: "{{ __('Date') }}",
                    data: 'date',
                    name: 'date',
                    defaultContent: '-',
                    class: 'text-center',
                },
                {
                    title: "Total Calls / Day",
                    data: 'sum_per_day',
                    name: 'sum_per_day',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "Avg Calls / Interval",
                    data: 'avg_per_day',
                    name: 'avg_per_day',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "% 00:00 - 01:00",
                    data: 'pct_00_01',
                    name: 'pct_00_01',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "% 01:00 - 02:00",
                    data: 'pct_01_02',
                    name: 'pct_01_02',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "% 02:00 - 03:00",
                    data: 'pct_02_03',
                    name: 'pct_02_03',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "% 03:00 - 04:00",
                    data: 'pct_03_04',
                    name: 'pct_03_04',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "% 04:00 - 05:00",
                    data: 'pct_04_05',
                    name: 'pct_04_05',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "% 05:00 - 06:00",
                    data: 'pct_05_06',
                    name: 'pct_05_06',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "% 06:00 - 07:00",
                    data: 'pct_06_07',
                    name: 'pct_06_07',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "% 07:00 - 08:00",
                    data: 'pct_07_08',
                    name: 'pct_07_08',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "% 08:00 - 09:00",
                    data: 'pct_08_09',
                    name: 'pct_08_09',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "% 09:00 - 10:00",
                    data: 'pct_09_10',
                    name: 'pct_09_10',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "% 10:00 - 11:00",
                    data: 'pct_10_11',
                    name: 'pct_10_11',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "% 11:00 - 12:00",
                    data: 'pct_11_12',
                    name: 'pct_11_12',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "% 12:00 - 13:00",
                    data: 'pct_12_13',
                    name: 'pct_12_13',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "% 13:00 - 14:00",
                    data: 'pct_13_14',
                    name: 'pct_13_14',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "% 14:00 - 15:00",
                    data: 'pct_14_15',
                    name: 'pct_14_15',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "% 15:00 - 16:00",
                    data: 'pct_15_16',
                    name: 'pct_15_16',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "% 16:00 - 17:00",
                    data: 'pct_16_17',
                    name: 'pct_16_17',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "% 17:00 - 18:00",
                    data: 'pct_17_18',
                    name: 'pct_17_18',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "% 18:00 - 19:00",
                    data: 'pct_18_19',
                    name: 'pct_18_19',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "% 19:00 - 20:00",
                    data: 'pct_19_20',
                    name: 'pct_19_20',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "% 20:00 - 21:00",
                    data: 'pct_20_21',
                    name: 'pct_20_21',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "% 21:00 - 22:00",
                    data: 'pct_21_22',
                    name: 'pct_21_22',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "% 22:00 - 23:00",
                    data: 'pct_22_23',
                    name: 'pct_22_23',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "% 23:00 - 00:00",
                    data: 'pct_23_00',
                    name: 'pct_23_00',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                }
            ],
            drawCallback: function() {
                $('.btn-tooltip').tooltip();
            }
        });
    </script>
@endsection
