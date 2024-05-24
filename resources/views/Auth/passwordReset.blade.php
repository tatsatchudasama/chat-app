<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
</head>

<body>

    <div class="card card-outline-secondary m-5">
        <div class="card-header">
            <h3 class="mb-0">Change Password</h3>
        </div>
        <div class="card-body">

            <form id="passwordResetForm" autocomplete="off">

                <meta name="csrf-token" content="{{ csrf_token() }}">

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email">
                    <div id="emailError" class="text-danger"></div>
                </div>

                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <div id="passwordError" class="text-danger"></div>
                </div>

                <a href="{{ route('login_view') }}">Login</a>

                <div class="form-group">
                    <button type="submit" class="btn btn-success btn-lg float-right" id="changePasswordButton">Change Password</button>
                </div>
            </form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#changePasswordButton').click(function(e) {
                e.preventDefault();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('#emailError').html('');
                $('#passwordError').html('');

                var formData = {
                    'email': $('#email').val(),
                    'password': $('#password').val()
                };

                $.ajax({
                    url: "{{ route('password_reset', ['token' => $token]) }}",
                    method: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            swal("Success", response.success, "success");
                            setTimeout(function() {
                                window.location.href = "{{ route('login_view') }}";
                            }, 2000);
                        } else {
                            swal("Error!", response.errors[0], "error");
                        }
                    },
                    error: function(error) {
                        if (error.responseJSON && error.responseJSON.errors) {
                            if (error.responseJSON.errors.email) {
                                $('#emailError').html(error.responseJSON.errors.email[0]);
                            }
                            if (error.responseJSON.errors.password) {
                                $('#passwordError').html(error.responseJSON.errors.password[0]);
                            }
                        } else {
                            swal("Error", "An unexpected error occurred.", "error");
                        }
                    }
                });
            });
        });
    </script>

</body>

</html>