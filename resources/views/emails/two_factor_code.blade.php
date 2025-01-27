<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two factor authentication</title>
</head>

<body style="font-family: 'Arial', sans-serif;">
    <section style="background-color: #f2f4f6; padding: 20px;">
        <div style="width: 500px; background: #fff; padding: 30px; margin: 0 auto;">
            <p style=" font-size: 1rem !important; color: #0047df !important; font-weight: normal !important; text-align: center;">Hello! {{ $user->name }}</p>
            <p style=" font-size: 1rem !important; color: #000 !important; font-weight: normal !important; text-align: center;">Someone is trying to log in with a new device.</p>
            <p style="margin-top: 50px !important; color: #002167 !important; text-align:center !important; margin-bottom: 0 !important;">Enter the following code to complete the login:</p>.
            <div style="  width: 100%; height: 40px; background: #faf9fa; border: 2px solid #dee2e6;">
                <p style="  font-size: 1rem !important; color: #666 !important; margin-bottom: 0 !important; font-weight: normal !important; text-align: center;">{{ $code }}</p>
            </div>
            <p style="  font-size: 1rem !important; color: #707c96 !important; text-align: center; margin-bottom: 0 !important; font-weight: normal !important; margin-top: 30px !important;">This code will expire in 5 minutes</p>
        </div>
    </section>
</body>

</html>