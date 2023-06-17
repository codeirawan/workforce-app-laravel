<div class="modal fade" id="modal-submit" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form method="POST" role="form" id="modal-submit-action" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Submit Confirmation') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="modal-submit-message"></p>
                    <textarea id="note" name="note" class="form-control" placeholder="{{ __('Note') }}">{{ old('note') }}</textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" id="btn-submit">{{ __('Submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var id = 0;

    $('#modal-submit').on('show.bs.modal', function(event) {
        var key = $(event.relatedTarget).data('key');
        id = $(event.relatedTarget).data('id');

        $('#modal-submit-message').text("{{ __('Does this ' . $object) }} (" + key +
            ") {{ __('submit') }} ?");
    });

    $('#btn-submit').click(function() {
        $('#modal-submit-action').attr('action', "{{ url('unpaid-leave') }}/" + id + "/submit/1").submit();
    });
</script>
