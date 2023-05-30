<div class="modal fade" id="modal-new-forecast" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form name="newForecast" action="{{ route('forecast.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Create New Forecast') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label for="city_id">{{ __('Site') }}</label>
                            <select id="city_id" name="city_id"
                                class="form-control kt_selectpicker @error('city_id') is-invalid @enderror" required
                                data-live-search="true" title="{{ __('Choose') }} {{ __('Site') }}">
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}"
                                        {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}</option>
                                @endforeach
                            </select>

                            @error('city_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="project_id">{{ __('Project') }}</label>
                            <select id="project_id" name="project_id"
                                class="form-control kt_selectpicker @error('project_id') is-invalid @enderror" required
                                data-live-search="true" title="{{ __('Choose') }} {{ __('Project') }}">
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="skill_id">{{ __('Skill') }}</label>
                            <select id="skill_id" name="skill_id"
                                class="form-control kt_selectpicker @error('skill_id') is-invalid @enderror" required
                                data-live-search="true" title="{{ __('Choose') }} {{ __('Skill') }}">
                                @foreach ($skills as $skill)
                                    <option value="{{ $skill->id }}"
                                        {{ old('skill_id') == $skill->id ? 'selected' : '' }}>
                                        {{ $skill->name }}</option>
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
                            <input type="number" class="form-control" name="avg_handling_time"
                                placeholder="Avg Handling Time" autocomplete="off" required>
                        </div>
                        <div class="form-group col-sm-4">
                            <input type="number" class="form-control" name="reporting_period"
                                placeholder="Reporting Period" autocomplete="off" required>
                        </div>
                        <div class="form-group col-sm-4">
                            <input type="number" class="form-control" name="service_level" placeholder="Service Level"
                                autocomplete="off" required>
                        </div>
                        <div class="form-group col-sm-4">
                            <input type="number" class="form-control" name="target_answer_time"
                                placeholder="Target Answer Time" autocomplete="off" required>
                        </div>
                        <div class="form-group col-sm-4">
                            <input type="number" class="form-control" name="shrinkage" placeholder="Shrinkage"
                                autocomplete="off" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ __('Cancel') }}</button>
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
