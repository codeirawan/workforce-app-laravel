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
                        <div class="form-group col-sm-6">
                            <label for="tipe">{{ __('User Type') }}</label>
                            <select class="form-control kt_selectpicker @error('tipe') is-invalid @enderror"
                                id="tipe" name="tipe" required data-live-search="true"
                                title="{{ __('Choose') }} {{ __('User Type') }}" required>
                                <option value="Corporate" {{ old('tipe') == 'Corporate' ? 'selected' : '' }}>
                                    {{ __('Corporate') }}</option>
                                <option value="Project" {{ old('tipe') == 'Project' ? 'selected' : '' }}>
                                    {{ __('Project') }}</option>
                            </select>

                            @error('tipe')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="wewenang">{{ __('Role') }}</label>
                            <select id="wewenang" name="wewenang"
                                class="form-control kt_selectpicker @error('wewenang') is-invalid @enderror" required
                                data-live-search="true" title="{{ __('Choose') }} {{ __('Role') }}">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ old('wewenang') == $role->id ? 'selected' : '' }}>{{ $role->display_name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('wewenang')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6 jabatan" id="field-position" style="display: none;">
                            <label for="jabatan">{{ __('Position') }}</label>
                            <select id="jabatan" name="jabatan"
                                class="form-control kt_selectpicker @error('jabatan') is-invalid @enderror"
                                data-live-search="true" title="{{ __('Choose') }} {{ __('Position') }}">
                                @foreach ($positions as $position)
                                    <option value="{{ $position->id }}"
                                        {{ old('jabatan') == $position->id ? 'selected' : '' }}>
                                        {{ $position->name }}</option>
                                @endforeach
                            </select>

                            @error('jabatan')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6 projek"id="field-project" style="display: none;">
                            <label for="projek">{{ __('Project') }}</label>
                            <select id="projek" name="projek"
                                class="form-control kt_selectpicker @error('projek') is-invalid @enderror"
                                data-live-search="true" title="{{ __('Choose') }} {{ __('Project') }}">
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ old('projek') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}</option>
                                @endforeach
                            </select>

                            @error('projek')
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
                                {{ __('Download bulk user templete') }} : <a
                                    href="{{ url('/') }}/template/bulk-user-template.xlsx">
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
<script>
    const tipeSelect = document.querySelector('#tipe');
    const durasiInput = document.querySelector('#field-position');
    const nilaiInput = document.querySelector('#field-project');

    tipeSelect.addEventListener('change', () => {
        if (tipeSelect.value === 'Corporate') {
            durasiInput.style.display = 'none';
            nilaiInput.style.display = 'none';
        } else {
            durasiInput.style.display = 'block';
            nilaiInput.style.display = 'block';
        }
    });
</script>
<script src="{{ asset(mix('js/form/validation.js')) }}"></script>
<script type="text/javascript">
    $('.kt_selectpicker').selectpicker({
        noneResultsText: "{{ __('No matching results for') }} {0}"
    });
</script>
