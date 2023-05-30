@extends('layouts.app')

@section('title')
    {{ __('Daily Volume') }} | {{ config('app.name') }}
@endsection

@section('style')
    <link href="{{ asset(mix('css/datatable.css')) }}" rel="stylesheet">
@endsection

@section('subheader')
    {{ __('History') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('history.daily') }}"
        class="kt-subheader__breadcrumbs-link">{{ __('Daily') }}</a>
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
                url: '{{ route('history.daily.data') }}',
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
                    title: "{{ __('Date') }}",
                    data: 'date',
                    name: 'date',
                    defaultContent: '-',
                    class: 'text-center',
                },
                {
                    title: "Total",
                    data: 'sum_per_day',
                    name: 'sum_per_day',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "Avg",
                    data: 'avg_per_day',
                    name: 'avg_per_day',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "Skill",
                    data: 'skill',
                    name: 'skill',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "Project",
                    data: 'project',
                    name: 'project',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "SIte",
                    data: 'site',
                    name: 'site',
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
        });
    </script>
@endsection
