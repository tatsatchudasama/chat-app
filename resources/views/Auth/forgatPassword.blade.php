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
        <h2>Forgat Password</h2>

        <form action="{{ route('password_change_email') }}" method="post" id="ForgotLoginForm">
            @csrf

            <div class="form-group">
                <label for="email">Email address:</label>
                <input type="email" class="form-control border" value="{{ old('email') }}" name="email" placeholder="Enter email">
                <div id="emailError" class="text-danger error-message"></div>
                @error('email')
                <div class="text-danger">{{ $message }}</div>
                @enderror

            </div>

            <button type="submit" class="btn btn-primary" id="saveBTN">Submit</button>

            <div class="mt-3">
                <a href="{{ route('login_view') }}">Login</a>
            </div>

        </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


</body>

</html>