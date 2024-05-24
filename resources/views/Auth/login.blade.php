<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Sidebar Menu</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="card card-outline-secondary m-5">
        <div class="card-header">
            <h3 class="mb-0">Login User</h3>
        </div>
        <div class="card-body">

            <form id="loginForm">
                <meta name="csrf-token" content="{{ csrf_token() }}">

                <div class="form-group">
                    <label for="email">Email address:</label>
                    <input type="email" class="form-control border" name="email" placeholder="Enter email">
                    <div id="emailError" class="text-danger error-message"></div>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control border" name="password" placeholder="Enter password">
                    <div id="passwordError" class="text-danger error-message"></div>
                </div>

                <button type="submit" class="btn btn-primary" id="saveBTN">Submit</button>

                <a href="{{ route('registration_view') }}">Register</a>

                <div class="mt-3">
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
                        Forgat Password
                    </button>
                </div>
            </form>

        </div>
    </div>


    <!-- forgot password email send model -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card-header">
                        <h3 class="mb-0">Change Password</h3>
                    </div>
                    <div class="card-body">

                        <form id="emailPasswordReset">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter Your Email">
                                <div id="sandEmailError" class="text-danger error-message"></div>
                            </div>
                        </form>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="sendEmail">Submit</button>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>
        $(document).ready(function() {

            // ===============================================================
            //                         Logging Form
            // ===============================================================
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#loginForm').submit(function(e) {

                e.preventDefault();

                var formData = $(this).serialize();

                $('.error-message').html('');

                swal("Logging", "Please wait...", "info");

                $.ajax({
                    url: "{{ route('login') }}",
                    method: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.status) {

                            setTimeout(function() {
                                swal.close();
                                window.location.href = response.redirect;
                            }, 1000);

                        } else {
                            swal("Error!", response.errors[0], "error");
                        }
                    },
                    error: function(error) {
                        $('#emailError').html(error.responseJSON.errors.email);
                        $('#passwordError').html(error.responseJSON.errors.password);
                    }
                });
            });

            // ===============================================================
            //                           Sand Email
            // ===============================================================
            $('#sendEmail').click(function(e) {
                e.preventDefault();

                $('.error-message').html('');

                var form = $('#emailPasswordReset')[0];
                var formData = new FormData(form);

                $('#sendEmail').prop("disabled", true);
                $('#buttonText').addClass('d-none');
                $('#buttonSpinner').removeClass('d-none');

                $.ajax({
                    url: "{{ route('password_change_email') }}",
                    method: "POST",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function(response) {

                        $('#sendEmail').prop("disabled", false);
                        swal("Success", response.success, "success");
                        $('#forgotPasswordModal').modal('hide');

                    },
                    error: function(error) {

                        $('#sendEmail').attr('disabled', false);
                        if (error.responseJSON && error.responseJSON.errors && error.responseJSON.errors.email) {
                            $('#sandEmailError').html(error.responseJSON.errors.email[0]);
                        } else {
                            swal("Error", "An unexpected error occurred.", "error");
                        }

                    },
                    complete: function() {
                        $('#sendEmail').prop("disabled", false);
                        $('#buttonText').removeClass('d-none');
                        $('#buttonSpinner').addClass('d-none');
                    }
                });
            });

        });
    </script>

</body>

</html>