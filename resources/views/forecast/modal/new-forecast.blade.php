<div class="modal fade" id="modal-new-forecast" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form action="{{ route('forecast.store') }}" method="POST" id="newForecast" name="newForecast">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Create New Forecast') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <label for="skill_id">{{ __('Skill') }}</label>
                            <select id="skill_id" name="skill_id"
                                class="form-control kt_selectpicker @error('skill_id') is-invalid @enderror" required
                                data-live-search="true" title="{{ __('Choose') }} {{ __('Skill') }}">
                                @foreach ($skills as $skill)
                                    <option value="{{ $skill->id }}"
                                        {{ old('skill_id') == $skill->id ? 'selected' : '' }}>
                                        {{ $skill->skill }} - {{ $skill->project }} {{ $skill->site }}</option>
                                @endforeach
                            </select>

                            @error('skill_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="start_date">{{ __('Start Date') }}</label>
                            <input type="text" class="form-control @error('start_date') is-invalid @enderror"
                                name="start_date" id="start_date"
                                placeholder="{{ __('Select') }} {{ __('Date') }}" readonly
                                value="{{ old('start_date') }}" required>

                            @error('start_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="end_date">{{ __('End Date') }}</label>
                            <input type="text" class="form-control @error('end_date') is-invalid @enderror"
                                name="end_date" id="end_date" placeholder="{{ __('Select') }} {{ __('Date') }}"
                                readonly value="{{ old('end_date') }}" required>

                            @error('end_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="avg_handling_time">{{ __('Avg Handling Time') }}</label>
                            <input type="number" class="form-control" min="0" id="avg_handling_time"
                                name="avg_handling_time" autocomplete="off" required>
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="reporting_period">{{ __('Reporting Period') }}</label>
                            <select class="form-control kt_selectpicker @error('reporting_period') is-invalid @enderror"
                                id="reporting_period" name="reporting_period" data-live-search="true">
                                <option value="3600" {{ old('reporting_period') == '3600' ? 'selected' : '' }}>
                                    {{ __('3600') }}</option>
                                <option value="1800" {{ old('reporting_period') == '1800' ? 'selected' : '' }}>
                                    {{ __('1800') }}</option>
                                <option value="900" {{ old('reporting_period') == '900' ? 'selected' : '' }}>
                                    {{ __('900') }}</option>
                            </select>

                            @error('reporting_period')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="service_level">{{ __('Service Level') }}</label>
                            <input type="number" class="form-control" min="0" id="service_level"
                                name="service_level" autocomplete="off" required>
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="target_answer_time">{{ __('Target Answer Time') }}</label>
                            <input type="number" class="form-control" min="0" id="target_answer_time"
                                name="target_answer_time" autocomplete="off" required>
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="shrinkage">{{ __('Shrinkage') }}</label>
                            <input type="number" class="form-control" min="0" id="shrinkage" name="shrinkage"
                                autocomplete="off" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="{{ asset(mix('js/form/validation.js')) }}"></script>
<script>
    $('#start_date').datepicker({
        autoclose: true,
        clearBtn: true,
        disableTouchKeyboard: true,
        format: "yyyy-mm-dd",
        language: "{{ config('app.locale') }}",
        startDate: "0d",
        weekStart: 1,
        templates: {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        },
        todayBtn: "linked",
        todayHighlight: true
    });
    $('#end_date').datepicker({
        autoclose: true,
        clearBtn: true,
        disableTouchKeyboard: true,
        format: "yyyy-mm-dd",
        language: "{{ config('app.locale') }}",
        startDate: "0d",
        weekStart: 1,
        templates: {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        },
        todayBtn: "linked",
        todayHighlight: true
    });
</script>
<script type="text/javascript">
    $('.kt_selectpicker').selectpicker({
        noneResultsText: "{{ __('No matching results for') }} {0}"
    });
</script>
