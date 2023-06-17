<div class="modal fade" id="modal-process" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" role="form" id="modal-process-action">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Process Confirmation') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="modal-process-message"></p>
                    <textarea id="note" name="note" class="form-control" placeholder="{{ __('Note') }}">{{ old('note') }}</textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="btn-reject">{{ __('Reject') }}</button>
                    <button type="button" class="btn btn-primary" id="btn-process">{{ __('Process') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var id = 0;

    $('#modal-process').on('show.bs.modal', function(event) {
        var key = $(event.relatedTarget).data('key');
        id = $(event.relatedTarget).data('id');

        $('#modal-process-message').text("{{ __('Does this ' . $object) }} (" + key +
            ") {{ __('process') }} ?");
    });

    $('#btn-reject').click(function() {
        $('#modal-process-action').attr('action', "{{ url('unpaid-leave') }}/" + id + "/process/0").submit();
    });

    $('#btn-process').click(function() {
        $('#modal-process-action').attr('action', "{{ url('unpaid-leave') }}/" + id + "/process/1").submit();
    });
</script>
