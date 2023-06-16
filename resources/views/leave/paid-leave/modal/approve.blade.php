<div class="modal fade" id="modal-approve" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" role="form" id="modal-approve-action">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Approve Confirmation') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="modal-approve-message"></p>
                    <textarea id="note" name="note" class="form-control" placeholder="{{ __('Note') }}">{{ old('note') }}</textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="btn-no">{{ __('No') }}</button>
                    <button type="button" class="btn btn-success" id="btn-yes">{{ __('Yes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var id = 0;

    $('#modal-approve').on('show.bs.modal', function(event) {
        var key = $(event.relatedTarget).data('key');
        id = $(event.relatedTarget).data('id');

        $('#modal-approve-message').text("{{ __('Does this ' . $object) }} (" + key +
            ") {{ __('approve') }} ?");
    });

    $('#btn-no').click(function() {
        $('#modal-approve-action').attr('action', "{{ url('paid-leave') }}/" + id + "/approve/0").submit();
    });

    $('#btn-yes').click(function() {
        $('#modal-approve-action').attr('action', "{{ url('paid-leave') }}/" + id + "/approve/1").submit();
    });
</script>
