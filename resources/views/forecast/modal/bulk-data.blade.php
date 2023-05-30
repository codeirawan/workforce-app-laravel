<div class="modal fade" id="modal-bulk-data" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form name="bulkData" action="{{ route('raw-data.bulk') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Bulk Data') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <label for="raw-data">{{ __('File upload extension : xls/xlsx, max size file') }} : 10
                                MB</label>
                            <div class="custom-file">
                                <input id="raw-data" name="raw-data" type="file" accept=".xls,.xlsx"
                                    class="custom-file-input @error('raw-data') is-invalid @enderror" required>

                                @error('raw-data')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <label class="custom-file-label" for="raw-data">{{ __('Choose file') }}</label>
                            </div>
                            <span>
                                {{ __('Download bulk data templete') }} : <a
                                    href="{{ url('/') }}/template/interval_60_minutes.xlsx">
                                    <h5 class="text-success">{{ __('here') }}!! <i class="fa fa-file-excel"></i>
                                    </h5>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Upload Bulk') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="{{ asset(mix('js/form/validation.js')) }}"></script>
