<script>
    $(document).ready(function() {
        /**
         * Generic confirmation dialog using SweetAlert2
         * @param {string} title - The title of the dialog
         * @param {string} message - The message to display
         * @param {string} icon - The icon to use (warning, error, success, info, question)
         * @param {function} callback - The function to call if confirmed
         */
        window.confirmDialog = function(title, message, icon, callback) {
            Swal.fire({
                title: title,
                text: message,
                icon: icon || 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed!'
            }).then((result) => {
                if (result.isConfirmed) {
                    callback();
                }
            });
        };

        /**
         * Confirm a form submission
         * @param {string} formId - The ID of the form to submit
         * @param {string} message - The confirmation message
         */
        window.confirmFormSubmit = function(formId, message) {
            confirmDialog(
                'Confirmation',
                message || 'Are you sure you want to proceed?',
                'question',
                function() {
                    document.getElementById(formId).submit();
                }
            );
        };

        /**
         * Confirm a link navigation
         * @param {string} url - The URL to navigate to
         * @param {string} message - The confirmation message
         */
        window.confirmNavigation = function(url, message) {
            confirmDialog(
                'Confirmation',
                message || 'Are you sure you want to proceed?',
                'question',
                function() {
                    window.location.href = url;
                }
            );
        };

        /**
         * Show a success message
         * @param {string} title - The title of the message
         * @param {string} message - The message to display
         */
        window.showSuccess = function(title, message) {
            Swal.fire({
                icon: 'success',
                title: title || 'Success',
                text: message,
            });
        };

        /**
         * Show an error message
         * @param {string} title - The title of the message
         * @param {string} message - The message to display
         */
        window.showError = function(title, message) {
            Swal.fire({
                icon: 'error',
                title: title || 'Error',
                text: message,
            });
        };

        /**
         * Confirm and trigger AJAX request
         * @param {string} url - The URL to send the request to
         * @param {string} method - The HTTP method (GET, POST, PUT, DELETE)
         * @param {Object} data - The data to send
         * @param {string} message - The confirmation message
         * @param {function} successCallback - The function to call on success
         */
        window.confirmAjax = function(url, method, data, message, successCallback) {
            confirmDialog(
                'Confirmation',
                message || 'Are you sure you want to proceed?',
                'question',
                function() {
                    $.ajax({
                        url: url,
                        type: method,
                        data: data,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (typeof successCallback === 'function') {
                                successCallback(response);
                            } else {
                                toastr.success(response.message || 'Operation successful');
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'An error occurred';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            toastr.error(errorMessage);
                        }
                    });
                }
            );
        };
    });
</script>
