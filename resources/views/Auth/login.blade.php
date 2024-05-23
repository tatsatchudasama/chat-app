<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Sidebar Menu</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container">
        <h2>Login Form</h2>

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
                <a href="{{ route('forgot_password') }}">Forgat Password</a>
            </div>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>
        $(document).ready(function() {

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
                            // window.location.href = response.redirect;

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
        });
    </script>


</body>

</html>