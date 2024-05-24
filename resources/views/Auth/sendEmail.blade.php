<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Forgot Email</title>
</head>

<body>

    <h1>Hello 👋</h1>

    <p>Email : {{ $email }}</p>
    <p>We received a request to reset your password. If this was you, click the following link to reset your password:</p>
    <a href="{{ $resetLink }}">Reset Password</a>
    <p>If you didn't request a password reset, you can safely ignore this email.</p>
    <p>Thank you,</p>

</body>

</html>
