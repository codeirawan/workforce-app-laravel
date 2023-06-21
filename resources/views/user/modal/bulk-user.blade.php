<div class="modal fade" id="modal-bulk-user" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form name="bulkUser" action="{{ route('user.bulk') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Bulk User') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <label for="role">{{ __('Role') }}</label>
                            <select id="role" name="role"
                                class="form-control kt_selectpicker @error('role') is-invalid @enderror" required
                                data-live-search="true" title="{{ __('Choose') }} {{ __('Role') }}">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ old('role') == $role->id ? 'selected' : '' }}>{{ $role->display_name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('role')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-sm-12">
                            <label for="file">{{ __('File upload extension : xls/xlsx, max size file') }} : 10
                                MB</label>
                            <div class="custom-file">
                                <input id="file" name="file" type="file" accept=".xls,.xlsx"
                                    class="custom-file-input @error('file') is-invalid @enderror" required>

                                @error('file')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <label class="custom-file-label" for="file">{{ __('Choose file') }}</label>
                            </div>
                            <span>
                                {{ __('Download bulk user templete') }} :
                                <br>
                                <a href="{{ url('/') }}/template/bulk_user_admin.xlsx">bulk_user_admin
                                    <i class="fa fa-file-excel text-success"></i>
                                </a>
                                <br>
                                <a href="{{ url('/') }}/template/bulk_user_spv.xlsx">bulk_user_spv
                                    <i class="fa fa-file-excel text-success"></i>
                                </a>
                                <br>
                                <a href="{{ url('/') }}/template/bulk_user_tl.xlsx">bulk_user_tl
                                    <i class="fa fa-file-excel text-success"></i>
                                </a>
                                <br>
                                <a href="{{ url('/') }}/template/bulk_user_agent.xlsx">bulk_user_agent
                                    <i class="fa fa-file-excel text-success"></i>
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
<script type="text/javascript">
    $('.kt_selectpicker').selectpicker({
        noneResultsText: "{{ __('No matching results for') }} {0}"
    });
</script>
