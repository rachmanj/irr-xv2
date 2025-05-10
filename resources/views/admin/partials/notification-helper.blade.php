<script>
    $(document).ready(function() {
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if (session('info'))
            toastr.info("{{ session('info') }}");
        @endif

        @if (session('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    });

    // Global notification functions for AJAX save processes
    function showSuccessNotification(message = 'Record saved successfully') {
        toastr.success(message);
    }

    function showErrorNotification(message = 'Failed to save record') {
        toastr.error(message);
    }

    // Function to handle AJAX response and show appropriate notification
    function handleAjaxResponse(response, successMessage = 'Record saved successfully', errorMessage =
        'Failed to save record') {
        if (response.success) {
            showSuccessNotification(successMessage);
            return true;
        } else {
            showErrorNotification(response.message || errorMessage);
            return false;
        }
    }
</script>
