<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f8fa;
            margin: 0;
            padding: 0;
        }
        .email-container {
            width: 100%;
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0,0,0,0.05);
        }
        .logo {
            text-align: center;
            margin-bottom: 25px;
        }
        .logo img {
            max-height: 60px;
        }
        .content h2 {
            text-align: center;
            color: #333333;
        }
        .otp {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            color: #2d3748;
            background-color: #edf2f7;
            padding: 12px 20px;
            margin: 20px auto;
            display: inline-block;
            border-radius: 8px;
            letter-spacing: 6px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #888;
            margin-top: 30px;
        }
        .center_display{
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="logo">
            <img src="http://65.0.105.46/admin/public/assets/media/logos/logo.png" alt="Company Logo">
        </div>

        <div class="content center_display">
            <h2>Dear {{ $dear_name ?? 'User' }},</h2>
            <p>Thank you for registering on PropertyOTP. Please use the following OTP to complete your registration:</p>

            <div class="otp">{{ $otp }}</div>

            <p>This OTP is valid for the 2 minutes. Do not share it with anyone.</p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} PropertyOTP. All rights reserved.<br>
            If you did not request this, please ignore this email or contact support.
        </div>
    </div>
</body>
</html>
