<html>
<body>
    <h1>Password Reset Request</h1>
    <p>Hello, {{ $user->name }}!</p>
    <p>You requested to reset your password. Click the link below to proceed:</p>
    <p><a href="{{ $resetUrl }}">Reset Password</a></p>
    <p>If you did not request a password reset, no further action is required.</p>
</body>
</html>
