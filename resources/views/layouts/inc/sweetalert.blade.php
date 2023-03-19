swal.fire({
    title: "{{ $title }}",
    text: "{{ $text }}",
    type: "{{ $type ?? 'error'}}",
    buttonsStyling: false,
    confirmButtonClass: "btn btn-{{ $color ?? 'primary' }}"
});