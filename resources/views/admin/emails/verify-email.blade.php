<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Email Verification - {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .email-header {
            background-color: rgb(255, 43, 53);
            color: #ffffff;
            text-align: center;
            padding: 25px 15px;
        }

        .email-header h2 {
            margin: 0;
            font-size: 24px;
        }

        .email-body {
            padding: 30px;
            color: #333;
        }

        .email-body h3 {
            color: #0d6efd;
            margin-top: 0;
        }

        .email-body p {
            font-size: 15px;
            line-height: 1.7;
            margin: 10px 0;
        }

        .btn {
            display: inline-block;
            background-color: rgb(255, 43, 53);
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 16px;
            transition: background 0.3s;
        }

        .btn:hover {
            background-color: #d72636;
        }

        .btn-container {
            text-align: center;
            margin: 30px 0;
        }

        .email-footer {
            background-color: rgb(255, 43, 53);
            text-align: center;
            color: #dcdcdc;
            font-size: 14px;
            padding: 15px;
        }

        @media (max-width: 600px) {
            .email-body {
                padding: 20px;
            }

            .email-header h2 {
                font-size: 20px;
            }

            .btn {
                padding: 10px 20px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
<div class="email-container">

    <!-- Header -->
    <div class="email-header">
        <h2>📧 Verify Your Email Address</h2>
    </div>

    <!-- Body -->
    <div class="email-body">
        <h3>Hello {{ $user['name'] }},</h3>
        <p>Thank you for joining <strong>{{ config('app.name') }}</strong>! We’re excited to have you on board.</p>

        <p>To complete your registration, please verify your email address by clicking the button below:</p>

        <div class="btn-container">
            <a href="{{ $verificationUrl }}" class="btn">Verify Email</a>
        </div>

        <p>If you didn’t create an account, you can safely ignore this email.</p>
    </div>

    <!-- Footer -->
    <div class="email-footer">
        © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </div>
</div>
</body>
</html>
