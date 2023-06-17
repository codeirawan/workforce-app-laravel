@extends('layouts.app')

@section('title')
    {{ __('Time Off') }} | {{ config('app.name') }}
@endsection

@section('style')
    <link href="{{ asset(mix('css/datatable.css')) }}" rel="stylesheet">
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('unpaid-leave.index') }}"
        class="kt-subheader__breadcrumbs-link">{{ __('Time Off') }}</a>
@endsection

@section('content')
    <div class="kt-portlet">
        <div class="kt-portlet__body">
            <div class="kt-portlet__content">
                @include('layouts.inc.alert')

                @if (Laratrust::isAbleTo('create-leave'))
                    <a href="{{ route('unpaid-leave.create') }}" class="btn btn-primary mb-4">
                        <i class="fa fa-plus"></i> {{ __('Request Time Off') }}
                    </a>
                @endif

                <table class="table" id="kt_table_1"></table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('layouts.inc.modal.delete', ['object' => 'time off'])
    @include('leave.unpaid-leave.modal.submit', ['object' => 'time off'])
    @include('leave.unpaid-leave.modal.process', ['object' => 'time off'])
    @include('leave.unpaid-leave.modal.approve', ['object' => 'time off'])
    @include('leave.unpaid-leave.modal.cancel')

    <script src="{{ asset(mix('js/datatable.js')) }}"></script>
    <script src="{{ asset(mix('js/tooltip.js')) }}"></script>
    <script type="text/javascript">
        $('#kt_table_1').DataTable({
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
                url: '{{ route('unpaid-leave.data') }}',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            order: [
                [0, 'desc']
            ],
            columns: [{
                    data: 'created_at',
                    name: 'created_at',
                    visible: false,
                },
                {
                    title: "{{ __('Request id') }}",
                    data: 'request_id',
                    name: 'request_id',
                    defaultContent: '-',
                    class: 'text-center'
                },
                {
                    title: "{{ __('Request by') }}",
                    data: 'name',
                    name: 'name',
                    defaultContent: '-',
                    class: 'text-center'
                },
                {
                    title: "Start date",
                    data: 'start_date',
                    name: 'start_date',
                    defaultContent: '-',
                    class: 'text-center',
                    render: function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            var startDate = new Date(data);
                            var formattedDate = startDate.getDate() + ' ' + startDate.toLocaleString(
                                'default', {
                                    month: 'short'
                                }) + ' ' + startDate.getFullYear();
                            return formattedDate;
                        }
                        return data;
                    }
                },
                {
                    title: "End date",
                    data: 'end_date',
                    name: 'end_date',
                    defaultContent: '-',
                    class: 'text-center',
                    render: function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            var endDate = new Date(data);
                            var formattedDate = endDate.getDate() + ' ' + endDate.toLocaleString(
                                'default', {
                                    month: 'short'
                                }) + ' ' + endDate.getFullYear();
                            return formattedDate;
                        }
                        return data;
                    }
                },
                // {
                //     title: "Total day",
                //     defaultContent: '-',
                //     class: 'text-center',
                //     render: function(data, type, row) {
                //         if (type === 'display' || type === 'filter') {
                //             var startDate = new Date(row.start_date);
                //             var endDate = new Date(row.end_date);
                //             var totalDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
                //             return totalDays;
                //         }
                //         return data;
                //     }
                // },
                {
                    title: "Note",
                    data: 'note',
                    name: 'note',
                    defaultContent: '-',
                    class: 'text-center'
                },
                {
                    title: "Status",
                    data: 'status',
                    name: 'status',
                    defaultContent: '-',
                    class: 'text-center'
                },
                {
                    title: "{{ __('Action') }}",
                    data: 'action',
                    name: 'action',
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
