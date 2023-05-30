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

                <table class="table" id="kt_table_1"></table>
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
                    title: "{{ __('Name') }}",
                    data: 'name',
                    name: 'name',
                    defaultContent: '-',
                    class: 'text-center'
                },
                {
                    title: "NIK",
                    data: 'nik',
                    name: 'nik',
                    defaultContent: '-',
                    class: 'text-center'
                },
                {
                    title: "Email",
                    data: 'email',
                    name: 'email',
                    defaultContent: '-',
                    class: 'text-center'
                },
                {
                    title: "{{ __('Role') }}",
                    data: 'role',
                    name: 'role',
                    defaultContent: '-',
                    class: 'text-center',
                    searchable: false
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
