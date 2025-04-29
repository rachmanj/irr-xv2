# Admin Notifications Guide

This document provides instructions on how to use Toastr for notifications and SweetAlert2 for confirmations in the admin section of the application.

## Toastr Notifications

Toastr is used for non-blocking notifications. The following types of notifications are available:

-   **Success**: `toastr.success('Your message here')`
-   **Error**: `toastr.error('Your message here')`
-   **Info**: `toastr.info('Your message here')`
-   **Warning**: `toastr.warning('Your message here')`

### Using Toastr with Laravel Flash Messages

In your controller, you can use Laravel's session flash messages:

```php
// Success message
return redirect()->route('admin.users.index')->with('success', 'User created successfully!');

// Error message
return redirect()->route('admin.users.index')->with('error', 'Failed to create user!');

// Info message
return redirect()->route('admin.users.index')->with('info', 'User updated!');

// Warning message
return redirect()->route('admin.users.index')->with('warning', 'This action cannot be undone!');
```

These flash messages will automatically be converted to Toastr notifications via the `admin.partials.notification-helper` include.

## SweetAlert2 Confirmations

SweetAlert2 is used for more interactive alerts and confirmations.

### Delete Confirmation

For delete operations, use the `admin.partials.delete-button` component:

```blade
@include('admin.partials.delete-button', [
    'id' => $user->id,
    'action' => route('admin.users.destroy', $user->id),
    'text' => 'Delete User' // Optional, defaults to "Delete"
])
```

This will create a delete button with a SweetAlert2 confirmation dialog.

### Custom SweetAlert2 Examples

```javascript
// Basic alert
Swal.fire(
    "Title",
    "Message",
    "info" // Can be success, error, warning, info, question
);

// Confirmation dialog
Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, proceed!",
}).then((result) => {
    if (result.isConfirmed) {
        // User clicked confirm button
        // Perform action here
    }
});

// Form input
Swal.fire({
    title: "Enter information",
    input: "text", // Can be text, textarea, select, email, number, etc.
    showCancelButton: true,
    confirmButtonText: "Submit",
    showLoaderOnConfirm: true,
    preConfirm: (inputValue) => {
        // Validate input
        if (!inputValue) {
            Swal.showValidationMessage("This field is required");
        }
        return inputValue;
    },
}).then((result) => {
    if (result.isConfirmed) {
        // User submitted the form with result.value
    }
});
```

## Example Page

Visit the example page at `/admin/examples/notifications` to see these components in action.

## Configuration

The default configuration for both Toastr and SweetAlert2 can be found in the `resources/views/layout/partials/script.blade.php` file. You can modify these settings as needed.
