@extends('layouts.admin')

@section('title')
    {{ __('Raw Data') }} | {{ config('app.name') }}
@endsection

@section('style')
    <link href="{{ asset(mix('css/datatable.css')) }}" rel="stylesheet">
@endsection

@section('subheader')
    {{ __('Bulk') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('raw-data.index') }}"
        class="kt-subheader__breadcrumbs-link">{{ __('Raw Data') }}</a>
@endsection

@section('content')
    <div class="kt-portlet">
        <div class="kt-portlet__body">
            <div class="kt-portlet__content">
                @include('layouts.inc.alert')

                @if (Laratrust::isAbleTo('create-forecast'))
                    <a href="#" class="btn btn-success mb-4" data-toggle="modal" data-target="#modal-bulk-data">
                        <i class="fa fa-file-excel"></i> {{ __('Bulk Raw Data') }}
                    </a>
                @endif

                <table class="table table-hover" id="kt_table_1"></table>

            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('layouts.inc.modal.delete', ['object' => 'data'])
    @include('forecast.raw-data.modal.bulk-data')

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
                url: '{{ route('raw-data.data') }}',
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
                    title: "00:00 - 01:00",
                    data: '00_01',
                    name: '00_01',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "01:00 - 02:00",
                    data: '01_02',
                    name: '01_02',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "02:00 - 03:00",
                    data: '02_03',
                    name: '02_03',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "03:00 - 04:00",
                    data: '03_04',
                    name: '03_04',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "04:00 - 05:00",
                    data: '04_05',
                    name: '04_05',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "05:00 - 06:00",
                    data: '05_06',
                    name: '05_06',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "06:00 - 07:00",
                    data: '06_07',
                    name: '06_07',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "07:00 - 08:00",
                    data: '07_08',
                    name: '07_08',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "08:00 - 09:00",
                    data: '08_09',
                    name: '08_09',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "09:00 - 10:00",
                    data: '09_10',
                    name: '09_10',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "10:00 - 11:00",
                    data: '10_11',
                    name: '10_11',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "11:00 - 12:00",
                    data: '11_12',
                    name: '11_12',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "12:00 - 13:00",
                    data: '12_13',
                    name: '12_13',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "13:00 - 14:00",
                    data: '13_14',
                    name: '13_14',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "14:00 - 15:00",
                    data: '14_15',
                    name: '14_15',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "15:00 - 16:00",
                    data: '15_16',
                    name: '15_16',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "16:00 - 17:00",
                    data: '16_17',
                    name: '16_17',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "17:00 - 18:00",
                    data: '17_18',
                    name: '17_18',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "18:00 - 19:00",
                    data: '18_19',
                    name: '18_19',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "19:00 - 20:00",
                    data: '19_20',
                    name: '19_20',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "20:00 - 21:00",
                    data: '20_21',
                    name: '20_21',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "21:00 - 22:00",
                    data: '21_22',
                    name: '21_22',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "22:00 - 23:00",
                    data: '22_23',
                    name: '22_23',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "23:00 - 00:00",
                    data: '23_00',
                    name: '23_00',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "Action",
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            drawCallback: function() {
                $('.btn-tooltip').tooltip();
            }
        });
    </script>
@endsection
