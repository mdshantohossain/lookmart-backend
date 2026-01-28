<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Password - {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            font-family: Arial, Helvetica, sans-serif;
        }

        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
            text-align: center;
        }

        .email-body p {
            font-size: 15px;
            line-height: 1.7;
            margin: 10px 0;
        }

        .btn {
            display: inline-block;
            background-color: rgb(255, 43, 53);
            color: #ffffff;
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
        <h2>🔒 Reset Your Password</h2>
    </div>

    <!-- Body -->
    <div class="email-body">
        <h3>Hello {{ $user->name }},</h3>
        <p>We received a request to reset your password for your <strong>{{ config('app.name') }}</strong> account.</p>
        <p>Please click the button below to set a new password:</p>

        <div class="btn-container">
            <a href="{{ $url }}" class="btn">Reset Password</a>
        </div>

        <p>If you did not request a password reset, no further action is required.</p>
        <p style="color: #888;">This link will expire in 60 minutes.</p>
    </div>

    <!-- Footer -->
    <div class="email-footer">
        © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </div>

</div>
</body>
</html>
