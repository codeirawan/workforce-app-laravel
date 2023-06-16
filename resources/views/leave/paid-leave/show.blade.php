@extends('layouts.app')

@section('title')
    {{ __('Leave Details') }} | {{ config('app.name') }}
@endsection

@section('style')
    <link href="{{ asset(mix('css/datatable.css')) }}" rel="stylesheet">
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('paid-leave.index') }}"
        class="kt-subheader__breadcrumbs-link">{{ __('Leave') }}</a>
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('paid-leave.show', $paidLeave->id) }}"
        class="kt-subheader__breadcrumbs-link">{{ $paidLeave->request_id }}</a>
@endsection

@section('content')
    <div class="kt-portlet" id="kt_page_portlet">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">{{ __('Leave Details') }}</h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <a href="{{ route('paid-leave.index') }}" class="btn btn-secondary">
                    <i class="la la-arrow-left"></i>
                    <span class="kt-hidden-mobile">{{ __('Back') }}</span>
                </a>
                @if (Laratrust::isAbleTo('update-leave') && $paidLeave->status == 'Draft')
                    <a href="{{ route('paid-leave.edit', $paidLeave->id) }}" class="btn btn-primary kt-margin-l-10">
                        <i class="la la-edit"></i>
                        <span class="kt-hidden-mobile">{{ __('Edit') }}</span>
                    </a>
                @endif
                @if (Laratrust::isAbleTo('update-leave') && $paidLeave->status == 'Draft')
                    <a href="#" data-id="{{ $paidLeave->id }}" data-toggle="modal" data-target="#modal-submit"
                        data-key="{{ $paidLeave->request_id }}" class="btn btn-warning kt-margin-l-10">
                        <i class="fa-regular fa-paper-plane"></i>
                        <span class="kt-hidden-mobile">{{ __('Submit') }}</span>
                    </a>
                @endif
                @if (Laratrust::isAbleTo('process-leave') && $paidLeave->status == 'Submitted')
                    <a href="#" data-id="{{ $paidLeave->id }}" data-toggle="modal" data-target="#modal-process"
                        data-key="{{ $paidLeave->request_id }}" class="btn btn-primary kt-margin-l-10">
                        <i class="la la-check"></i>
                        <span class="kt-hidden-mobile">{{ __('Process') }}</span>
                    </a>
                @endif
                @if (Laratrust::isAbleTo('approve-leave') && $paidLeave->status == 'Processed')
                    <a href="#" data-id="{{ $paidLeave->id }}" data-toggle="modal" data-target="#modal-approve"
                        data-key="{{ $paidLeave->request_id }}" class="btn btn-success kt-margin-l-10">
                        <i class="fa fa-check-double"></i>
                        <span class="kt-hidden-mobile">{{ __('Approve') }}</span>
                    </a>
                @endif
                @if (Laratrust::isAbleTo('cancel-leave') && ($paidLeave->status == 'Submitted' || $paidLeave->status == 'Processed'))
                    <a href="#" data-href="{{ route('paid-leave.cancel', $paidLeave->id) }}" data-toggle="modal"
                        data-target="#modal-cancel" class="btn btn-dark kt-margin-l-10">
                        <i class="la la-ban"></i>
                        <span class="kt-hidden-mobile">{{ __('Cancel') }}</span>
                    </a>
                @endif
                @if (Laratrust::isAbleTo('delete-leave') && $paidLeave->status == 'Draft')
                    <a href="#" data-href="{{ route('paid-leave.destroy', $paidLeave->id) }}"
                        class="btn btn-danger kt-margin-l-10" title="{{ __('Delete') }}" data-toggle="modal"
                        data-target="#modal-delete" data-key="{{ $paidLeave->request_id }}">
                        <i class="la la-trash"></i>
                        <span class="kt-hidden-mobile">{{ __('Delete') }}</span>
                    </a>
                @endif
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="kt-section kt-section--first">
                <div class="kt-section__body">
                    @include('layouts.inc.alert')

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label>{{ __('Request Name') }}</label>
                            <input type="text" class="form-control" value="{{ $paidLeave->name }}" disabled>
                        </div>

                        <div class="form-group col-sm-6">
                            <label>{{ __('Leave Type') }}</label>
                            <input type="text" class="form-control" value="{{ $paidLeave->type }}" disabled>
                        </div>

                        <div class="form-group col-sm-6">
                            <label>{{ __('Start Date') }}</label>
                            <input type="text" class="form-control" disabled value="{{ $paidLeave->start_date }}">
                        </div>

                        <div class="form-group col-sm-6">
                            <label>{{ __('End Date') }}</label>
                            <input type="text" class="form-control" disabled value="{{ $paidLeave->end_date }}">
                        </div>

                        <div class="form-group col-sm-12">
                            <label>{{ __('Note') }}</label>
                            <textarea type="text" class="form-control" disabled>{{ $paidLeave->note }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__foot">
            <div class="kt-section kt-section--first">
                <h3 class="kt-section__title">{{ __('Leave Status') }}</h3>

                <div class="kt-section__body">
                    <div class="kt-list kt-list--badge">
                        @foreach ($paidLeaveStatus as $sLeave)
                            @php
                                $color = $sLeave->status == 'Draft' ? 'metal' : ($sLeave->status == 'Submitted' ? 'warning' : ($sLeave->status == 'Approved' ? 'success' : ($sLeave->status == 'Rejected' ? 'danger' : ($sLeave->status == 'Canceled' ? 'dark' : ($sLeave->status == 'Processed' ? 'primary' : 'secondary')))));
                                $icon = $sLeave->status == 'Draft' ? 'hourglass-half' : ($sLeave->status == 'Submitted' ? 'paper-plane' : ($sLeave->status == 'Approved' ? 'check-double' : ($sLeave->status == 'Rejected' ? 'times' : ($sLeave->status == 'Canceled' ? 'ban' : ($sLeave->status == 'Processed' ? 'check' : 'user-times')))));
                                $note = $sLeave->status == 'Rejected' || $sLeave->status == 'Canceled' ? Lang::get('Reason') . ': ' . $sLeave->note : Lang::get('Note') . ': ' . $sLeave->note;
                            @endphp

                            <div class="kt-list__item">
                                <span class="kt-list__badge kt-list__badge--{{ $color }}"></span>
                                <span class="kt-list__icon"><i
                                        class="fa fa-{{ $icon }} kt-font-{{ $color }}"
                                        style="font-size: 1.6rem;"></i></span>
                                <span class="kt-list__text">{{ __($sLeave->status) }} {{ __('by') }} <a
                                        href="{{ route('user.show', $sLeave->user_id) }}"
                                        class="kt-link">{{ $sLeave->user_name }}</a>. {{ $note }}</span>
                                <span class="kt-list__time w-25">{{ $sLeave->at->diffForHumans(now()) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('leave.paid-leave.modal.submit', ['object' => 'leave'])
    @if ($paidLeave->status == 'Submitted')
        @include('leave.paid-leave.modal.process', ['object' => 'leave'])
    @endif
    @if ($paidLeave->status == 'Processed')
        @include('leave.paid-leave.modal.approve', ['object' => 'leave'])
    @endif
    @include('leave.paid-leave.modal.cancel')
    @include('layouts.inc.modal.delete', ['object' => 'leave'])

    <script src="{{ asset(mix('js/datatable.js')) }}"></script>
    <script src="{{ asset(mix('js/tooltip.js')) }}"></script>
    <script src="{{ asset(mix('js/form/validation.js')) }}"></script>
@endsection
