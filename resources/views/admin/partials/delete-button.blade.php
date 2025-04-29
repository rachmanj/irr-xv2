<form id="delete-form-{{ $id }}" action="{{ $action }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<button type="button" class="btn btn-danger btn-xs delete-btn" data-form-id="delete-form-{{ $id }}">
    {{ $text ?? 'Delete' }}
</button>

<script>
    $(document).ready(function() {
        $('.delete-btn').on('click', function() {
            var formId = $(this).data('form-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        });
    });
</script>
