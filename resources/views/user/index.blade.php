@extends('layouts.app')

@section('title')
    {{ __('User') }} | {{ config('app.name') }}
@endsection

@section('style')
    <link href="{{ asset(mix('css/datatable.css')) }}" rel="stylesheet">
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('user.index') }}"
        class="kt-subheader__breadcrumbs-link">{{ __('User') }}</a>
@endsection

@section('content')
    <div class="kt-portlet">
        <div class="kt-portlet__body">
            <div class="kt-portlet__content">
                @include('layouts.inc.alert')

                @if (Laratrust::isAbleTo('create-user'))
                    <a href="{{ route('user.create') }}" class="btn btn-primary mb-4">
                        <i class="fa fa-plus"></i> {{ __('New User') }}
                    </a>
                    <a href="#" class="btn btn-success mb-4" data-toggle="modal" data-target="#modal-bulk-user">
                        <i class="fa fa-file-excel"></i> {{ __('Bulk User') }}
                    </a>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="kt_table_1"></table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('user.modal.bulk-user')
    @include('layouts.inc.modal.delete', ['object' => 'user'])
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
                url: '{{ route('user.data') }}',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [{
                    title: "ID",
                    data: 'nik',
                    name: 'nik',
                    defaultContent: '-',
                    class: 'text-center',
                    visible: false
                },
                {
                    title: "{{ __('Name') }}",
                    data: 'name',
                    name: 'name',
                    defaultContent: '-'
                },
                {
                    title: "Email",
                    data: 'email',
                    name: 'email',
                    defaultContent: '-',
                    visible: false
                },
                {
                    title: "Gender",
                    data: 'gender',
                    name: 'gender',
                    defaultContent: '-',
                    class: 'text-center'
                },
                {
                    title: "Religion",
                    data: 'religion',
                    name: 'religion',
                    defaultContent: '-',
                    class: 'text-center'
                },
                {
                    title: "{{ __('Role') }}",
                    data: 'role',
                    name: 'role',
                    defaultContent: '-',
                    class: 'text-center'
                },
                {
                    title: "TL",
                    data: 'team_leader_name',
                    name: 'team_leader_name',
                    defaultContent: '-',
                    class: 'text-center'
                },
                {
                    title: "SPV",
                    data: 'supervisor_name',
                    name: 'supervisor_name',
                    defaultContent: '-',
                    class: 'text-center'
                },
                {
                    title: "Site",
                    data: 'site',
                    name: 'site',
                    defaultContent: '-'
                },
                {
                    title: "Project",
                    data: 'project',
                    name: 'project',
                    defaultContent: '-'
                },
                {
                    title: "Skill",
                    data: 'skill',
                    name: 'skill',
                    defaultContent: '-'
                },
                {
                    title: "Join Date",
                    data: 'join_date',
                    name: 'join_date',
                    defaultContent: '-',
                    class: 'text-center',
                    visible: false
                },
                {
                    title: "Initial Leave",
                    data: 'initial_leave',
                    name: 'initial_leave',
                    defaultContent: '-',
                    class: 'text-center',
                    visible: false
                },
                {
                    title: "Used Leave",
                    data: 'used_leave',
                    name: 'used_leave',
                    defaultContent: '-',
                    class: 'text-center',
                    visible: false
                },
                {
                    title: "Remaining Leave",
                    data: null,
                    name: 'remaining_leave',
                    defaultContent: '-',
                    class: 'text-center',
                    render: function(data, type, row) {
                        var remainingLeave = row.initial_leave - row.used_leave;
                        return remainingLeave;
                    },
                    visible: false
                },
                {
                    title: "{{ __('Active') }}",
                    data: 'active',
                    name: 'active',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false
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
