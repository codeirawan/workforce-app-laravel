@extends('layouts.app')

@section('title')
    {{ __('Activity') }} | {{ config('app.name') }}
@endsection

@section('style')
    <link href="{{ asset(mix('css/datatable.css')) }}" rel="stylesheet">
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('master.activity.index') }}"
        class="kt-subheader__breadcrumbs-link">{{ __('Activity') }}</a>
@endsection

@section('content')
    <div class="kt-portlet">
        <div class="kt-portlet__body">
            <div class="kt-portlet__content">
                @include('layouts.inc.alert')

                @if (Laratrust::isAbleTo('create-master'))
                    <a href="{{ route('master.activity.create') }}" class="btn btn-primary mb-4">
                        <i class="fa fa-plus"></i> {{ __('New Activity') }}
                    </a>
                @endif

                <table class="table" id="kt_table_1"></table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('layouts.inc.modal.delete', ['object' => 'activity'])

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
                url: '{{ route('master.activity.data') }}',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [{
                    title: "{{ __('Name') }}",
                    data: 'name',
                    name: 'name',
                    defaultContent: '-',
                    class: 'text-center',
                },
                {
                    title: "{{ __('Duration') }}",
                    data: 'duration',
                    name: 'duration',
                    defaultContent: '-',
                    class: 'text-center',
                },
                {
                    title: "{{ __('Color') }}",
                    data: 'color',
                    name: 'color',
                    defaultContent: '-',
                    class: 'text-center',
                    render: function(data, type, full, meta) {
                        if (type === 'display') {
                            return '<div style="width: 20px; height: 20px; background-color: ' + data +
                                '; margin: 0 auto;"></div>';
                        }
                        return data;
                    }
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
