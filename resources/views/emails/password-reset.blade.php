<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>
<body style="margin:0; padding:0; background-color:#edf2f7; font-family:Arial, Helvetica, sans-serif; color:#3d4852;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#edf2f7; margin:0; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="570" cellpadding="0" cellspacing="0" style="width:570px; max-width:100%; background-color:#ffffff; border:1px solid #e5e7eb;">
                    <tr>
                        <td align="center" style="padding:24px 32px 8px;">
                            <img src="{{ asset('images/app-name.png') }}" alt="{{ config('app.name') }}" style="max-width:220px; height:auto; display:block;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px 32px 32px; font-size:16px; line-height:1.6;">
                            <h1 style="margin:0 0 16px; font-size:22px; color:#111827;">Hello!</h1>
                            <p style="margin:0 0 16px;">We received a request to reset the password for your Academix account.</p>
                            <p style="margin:0 0 24px; text-align:center;">
                                <a href="{{ $url }}" style="display:inline-block; background-color:#111827; color:#ffffff; text-decoration:none; padding:12px 24px; border-radius:6px;">Reset Password</a>
                            </p>
                            <p style="margin:0 0 16px;">This password reset link will expire in 60 minutes.</p>
                            <p style="margin:0 0 24px;">If you did not request a password reset, no further action is required.</p>
                            <p style="margin:0 0 8px;">If you're having trouble clicking the button, copy and paste this URL into your browser:</p>
                            <p style="margin:0; word-break:break-all;">
                                <a href="{{ $url }}" style="color:#2563eb;">{{ $url }}</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
