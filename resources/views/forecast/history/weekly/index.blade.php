@extends('layouts.app')

@section('title')
    {{ __('Weekly Volume') }} | {{ config('app.name') }}
@endsection

@section('style')
    <link href="{{ asset(mix('css/datatable.css')) }}" rel="stylesheet">
@endsection

@section('subheader')
    {{ __('History') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('history.weekly') }}"
        class="kt-subheader__breadcrumbs-link">{{ __('Weekly') }}</a>
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
    <script src="{{ asset(mix('js/datatable.js')) }}"></script>
    <script src="{{ asset(mix('js/tooltip.js')) }}"></script>
    <script src="{{ asset(mix('js/form/validation.js')) }}"></script>
    <script type="text/javascript">
        $('#data').DataTable({
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
                url: '{{ route('history.weekly.data') }}',
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
                    data: 'sum_per_week',
                    name: 'sum_per_week',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    title: "{{ __('Avg') }}",
                    data: 'avg_per_week',
                    name: 'avg_per_week',
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

            ],
            drawCallback: function() {
                $('.btn-tooltip').tooltip();
            }
        });
    </script>
@endsection
