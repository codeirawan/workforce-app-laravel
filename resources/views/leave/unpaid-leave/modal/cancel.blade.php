<div class="modal fade" id="modal-cancel" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="kt_form_1" method="POST" role="form" class="modal-cancel-action">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Cancel Leave') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="reason">{{ __('Reason') }}</label>
                    <textarea id="reason" name="reason" class="form-control @error('reason') is-invalid @enderror" required>{{ old('reason') }}</textarea>

                    @error('reason')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-dark">{{ __('Cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#modal-cancel').on('show.bs.modal', function(event) {
        $('.modal-cancel-action').attr('action', $(event.relatedTarget).data('href'));
    });
</script>
