<!DOCTYPE html>
<html>

<head>
    <title>Password Reset</title>
</head>

<body>
    <p>Hello</p>

    <p>We received a request to reset your password. To reset your password, please click the button below:</p>

    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <a href="http://localhost:3000/reset-password/{{ $data['token'] }}" target="_blank">Reset Password</a>
            </td>
        </tr>
    </table>

    <p>If you did not request a password reset, please ignore this email.</p>

    <p>Thank you,</p>
    <p>{{ config('app.name') }}</p>
</body>

</html>
