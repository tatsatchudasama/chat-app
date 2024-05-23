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
            <h3 class="mb-0">Registration Form</h3>
        </div>
        <div class="card-body">
            <form id="register">
                <meta name="csrf-token" content="{{ csrf_token() }}">

                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control border" name="name" placeholder="Enter name">
                    <div id="nameError" class="text-danger error-message"></div>
                </div>

                <div class="form-group">
                    <label for="email">Email address:</label>
                    <input type="email" class="form-control border" name="email" placeholder="Enter email">
                    <div id="emailError" class="text-danger error-message"></div>
                </div>

                <div class="form-group">
                    <label for="mobile">Mobile Number:</label>
                    <input type="text" class="form-control border" name="phone" placeholder="Enter mobile number" maxlength="10">
                    <div id="phoneError" class="text-danger error-message"></div>
                </div>

                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea class="form-control border" cols="3" rows="3" name="address" placeholder="Enter address"></textarea>
                    <div id="addressError" class="text-danger error-message"></div>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control border" name="password" placeholder="Enter password">
                    <div id="passwordError" class="text-danger error-message"></div>
                </div>

                <button type="submit" class="btn btn-primary" id="saveBTN">Submit</button>

                <a href="{{ route('login_view') }}">Login</a>

                <div class="mt-3">
                    <a href="{{ route('forgot_password') }}">Forgat Password</a>
                </div>

            </form>

        </div>
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

            var form = $('#register')[0];

            $('#saveBTN').click(function(e) {

                e.preventDefault();

                $('.error-message').html('');

                var formData = new FormData(form);

                $.ajax({
                    url: "{{ route('registration') }}",
                    method: "POST",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function(response) {

                        swal("success", response.success, "success");
                        $('#register')[0].reset();
                        $('#saveBTN').prop("disabled", false);

                    },
                    error: function(error) {

                        $('#saveBTN').attr('disabled', false);

                        $('#nameError').html(error.responseJSON.errors.name);
                        $('#emailError').html(error.responseJSON.errors.email);
                        $('#phoneError').html(error.responseJSON.errors.phone);
                        $('#addressError').html(error.responseJSON.errors.address);
                        $('#passwordError').html(error.responseJSON.errors.password);

                    }
                });

            });

        });
    </script>

</body>

</html>