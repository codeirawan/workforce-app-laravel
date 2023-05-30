@extends('layouts.app')

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
                <div class="card-body table-responsive">
                    <table class="table table-hover" id="kt_table_1"></table>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('layouts.inc.modal.delete', ['object' => 'data'])
    @include('forecast.modal.bulk-data')

    <script src="{{ asset(mix('js/datatable.js')) }}"></script>
    <script src="{{ asset(mix('js/tooltip.js')) }}"></script>
    <script src="{{ asset(mix('js/form/validation.js')) }}"></script>
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
                    title: "{{ __('Date') }}",
                    data: 'date',
                    name: 'date',
                    defaultContent: '-',
                    class: 'text-center',
                },
                {
                    title: "{{ __('Start Time') }}",
                    data: 'start_time',
                    name: 'start_time',
                    defaultContent: '-',
                    class: 'text-center',
                },
                {
                    title: "{{ __('End Time') }}",
                    data: 'end_time',
                    name: 'end_time',
                    defaultContent: '-',
                    class: 'text-center',
                },
                {
                    title: "{{ __('Volume') }}",
                    data: 'volume',
                    name: 'volume',
                    defaultContent: '-',
                    class: 'text-center',
                },
                {
                    title: "{{ __('Site') }}",
                    data: 'site',
                    name: 'site',
                    defaultContent: '-',
                    class: 'text-center',
                },
                {
                    title: "{{ __('Project') }}",
                    data: 'project',
                    name: 'project',
                    defaultContent: '-',
                    class: 'text-center',
                },
                {
                    title: "{{ __('Skill') }}",
                    data: 'skill',
                    name: 'skill',
                    defaultContent: '-',
                    class: 'text-center',
                },
                {
                    title: "{{ __('Batch') }}",
                    data: 'batch_id',
                    name: 'batch_id',
                    defaultContent: '-',
                    class: 'text-center',
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
