@extends('layouts.app')

@section('title')
    {{ __('Forecast') }} | {{ config('app.name') }}
@endsection

@section('style')
    <link href="{{ asset(mix('css/datatable.css')) }}" rel="stylesheet">
@endsection

@section('subheader')
    {{ __('Data') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('raw-data.index') }}"
        class="kt-subheader__breadcrumbs-link">{{ __('Forecast') }}</a>
@endsection

@section('content')
    <div class="kt-portlet">
        <div class="kt-portlet__body">
            <div class="kt-portlet__content">
                @include('layouts.inc.alert')

                @if (Laratrust::isAbleTo('create-forecast'))
                    <a href="#" class="btn btn-primary mb-4" data-toggle="modal" data-target="#modal-new-forecast">
                        <i class="fa-solid fa-magnifying-glass-chart"></i> {{ __('New Forecast') }}
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
    @include('forecast.modal.new-forecast')

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
                url: '{{ route('forecast.params') }}',
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
                    title: "{{ __('Start') }}",
                    data: 'start_date',
                    name: 'start_date',
                    defaultContent: '-',
                    class: 'text-center',
                    render: function(data) {
                        return moment(data).format('DD MMM YYYY');
                    }
                },
                {
                    title: "{{ __('End') }}",
                    data: 'end_date',
                    name: 'end_date',
                    defaultContent: '-',
                    class: 'text-center',
                    render: function(data) {
                        return moment(data).format('DD MMM YYYY');
                    }
                },
                {
                    title: "{{ __('Site') }}",
                    data: 'site',
                    name: 'site',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "{{ __('Project') }}",
                    data: 'project',
                    name: 'project',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "{{ __('Skill') }}",
                    data: 'skill',
                    name: 'skill',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "{{ __('AHT') }}",
                    data: 'avg_handling_time',
                    name: 'avg_handling_time',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "{{ __('Period') }}",
                    data: 'reporting_period',
                    name: 'reporting_period',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "{{ __('SL') }}",
                    data: 'service_level',
                    name: 'service_level',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "{{ __('TAT') }}",
                    data: 'target_answer_time',
                    name: 'target_answer_time',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "{{ __('Shrinkage') }}",
                    data: 'shrinkage',
                    name: 'shrinkage',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
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
            order: [
                [1, 'desc']
            ],
            drawCallback: function() {
                $('.btn-tooltip').tooltip();
            }
        });
    </script>
@endsection
