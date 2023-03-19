<script src="{{ asset(mix('js/form/thousand-separator.js')) }}"></script>
<script type="text/javascript">
    $('.separator.currency').number(true, 2);
    $('.separator').not('.separator.currency').number(true, 0);
    $('.separator').keyup(function() {
        $(this).next('.separator-hidden').val($(this).val());
    }).change(function() {
        $(this).next('.separator-hidden').val($(this).val());
    });
</script>