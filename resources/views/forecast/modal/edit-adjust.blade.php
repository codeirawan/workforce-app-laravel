<div class="modal fade" id="modal-edit-adjust" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form action="{{ route('forecast.adjust.update', $params->id) }}" method="POST" id="editAdjust"
                name="editAdjust">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Edit Adjustment Forecast') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="mon">{{ __('Monday') }}</label>
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-percent"></i>
                                    </span>
                                </div>
                                <input type="number" class="form-control" placeholder="Adjust monday" id="mon"
                                    name="mon" value="{{ old('mon', $adjust->mon) }}" autocomplete="off" required />
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="tue">{{ __('Tuesday') }}</label>
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-percent"></i>
                                    </span>
                                </div>
                                <input type="number" class="form-control" placeholder="Adjust tuesday" id="tue"
                                    name="tue" value="{{ old('tue', $adjust->tue) }}" autocomplete="off" required />
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="wed">{{ __('Wednesday') }}</label>
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-percent"></i>
                                    </span>
                                </div>
                                <input type="number" class="form-control" placeholder="Adjust wednesday" id="wed"
                                    name="wed" value="{{ old('wed', $adjust->wed) }}" autocomplete="off" required />
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="thu">{{ __('Thursday') }}</label>
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-percent"></i>
                                    </span>
                                </div>
                                <input type="number" class="form-control" placeholder="Adjust thursday" id="thu"
                                    name="thu" value="{{ old('thu', $adjust->thu) }}" autocomplete="off" required />
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="fri">{{ __('Friday') }}</label>
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-percent"></i>
                                    </span>
                                </div>
                                <input type="number" class="form-control" placeholder="Adjust friday" id="fri"
                                    name="fri" value="{{ old('fri', $adjust->fri) }}" autocomplete="off"
                                    required />
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="sat">{{ __('Saturday') }}</label>
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-percent"></i>
                                    </span>
                                </div>
                                <input type="number" class="form-control" placeholder="Adjust saturday" id="sat"
                                    name="sat" value="{{ old('sat', $adjust->sat) }}" autocomplete="off"
                                    required />
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="sun">{{ __('Sunday') }}</label>
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-percent"></i>
                                    </span>
                                </div>
                                <input type="number" class="form-control" placeholder="Adjust sunday"
                                    id="sun" name="sun" value="{{ old('sun', $adjust->sun) }}"
                                    autocomplete="off" required />
                            </div>
                        </div>
                    </div> --}}
                    @php
                        $days = ['mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 'thu' => 'Thursday', 'fri' => 'Friday', 'sat' => 'Saturday', 'sun' => 'Sunday'];
                    @endphp

                    <div class="row">
                        @foreach ($days as $dayKey => $dayLabel)
                            <div class="form-group col-sm-6">
                                <label for="{{ $dayKey }}">{{ __($dayLabel) }}</label>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="fa-solid fa-percent"></i>
                                        </span>
                                    </div>
                                    <input type="number" class="form-control"
                                        placeholder="Adjust {{ strtolower($dayLabel) }}" id="{{ $dayKey }}"
                                        name="{{ $dayKey }}" value="{{ old($dayKey, $adjust->$dayKey) }}"
                                        autocomplete="off" required />
                                </div>
                            </div>
                        @endforeach
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
