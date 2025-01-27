<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>verify account</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f2f4f6; margin: 0; padding: 0;">
    <section style="width:100%; background-color:#f2f4f6; padding:20px; overflow:hidden;">
        <div style="width: 50%; margin: 0 auto;  text-align: center;">
            <p style="font-size: 1rem; color: #000;">Hello {{ $user->name }}</p>
            <p style="font-size: 1rem; color: #000;">Thank you for registering. Please click the link below to verify your email address:</p>
            <a href="{{ $verificationUrl }}" style="background: #002167; border: none; color: #fff; padding: 10px 30px; text-align: center; border-radius: 10px; text-decoration: none; margin-top: 20px;">Verify Email Address</a>
            <p style="font-size: 1rem; color: #dee2e6; margin-top: 20px;">If you did not register, please ignore this email.</p>
        </div>
    </section>
</body>

</html>