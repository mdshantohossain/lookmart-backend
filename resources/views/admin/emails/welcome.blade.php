<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to {{ config('app.name') }}</title>
</head>
<body style="margin:0; padding:0; font-family:'Segoe UI', Arial, Helvetica, sans-serif;">
<div style="max-width:650px; margin:40px auto; background:#ffffff; border-radius:10px; box-shadow:0 4px 18px rgba(0,0,0,0.08); overflow:hidden;">

    <!-- Header -->
    <div style="background-color:rgb(255, 43, 53); color:#ffffff; text-align:center; padding:35px 20px;">
        <h1 style="margin:0; font-size:28px; font-weight:700; letter-spacing:0.5px;">
            Welcome to {{ config('app.name') }} 🎉
        </h1>
        <p style="margin-top:8px; font-size:15px; color:#ffeaea;">
            Your journey to smarter shopping starts now!
        </p>
    </div>

    <!-- Body -->
    <div style="padding:35px 30px 40px 30px; color:#333;">
        <h2 style="color:rgb(255, 43, 53); margin-bottom:10px;">Hey {{ $userName }},</h2>
        <p style="font-size:16px; line-height:1.7; margin-top:0;">
            We’re absolutely <strong>thrilled</strong> to have you on board!
            At <strong>{{ config('app.name') }}</strong>, we believe shopping should be simple, inspiring, and rewarding.
            You now have full access to explore exclusive deals, manage your orders, and personalize your experience.
        </p>

        <div style="background-color:#fef2f2; border-left:4px solid rgb(255, 43, 53); padding:15px 20px; margin:25px 0; border-radius:6px;">
            <p style="margin:0; font-size:15px; color:#444;">
                ✨ <strong>Pro Tip:</strong> Complete your profile to unlock personalized product recommendations and faster checkout!
            </p>
        </div>

        <h3 style="margin-bottom:10px; color:#222;">What’s next?</h3>
        <ul style="font-size:15px; color:#555; line-height:1.8; padding-left:20px; margin-top:0;">
            <li>Explore your <strong>dashboard</strong> to track activity.</li>
            <li>Update your <strong>profile</strong> for a tailored experience.</li>
            <li>Start <strong>shopping</strong> your favorite products today!</li>
        </ul>

        <div style="text-align:center; margin-top:35px;">
            <a href="{{ env('FRONTEND_URL') . '/dashboard' }}"
               style="background-color:rgb(255, 43, 53); color:#fff; text-decoration:none;
                          padding:14px 35px; border-radius:8px; display:inline-block;
                          font-size:17px; font-weight:600; box-shadow:0 3px 6px rgba(255,43,53,0.3);">
                Go to Dashboard
            </a>
        </div>
    </div>

    <!-- Footer -->
    <div style="background-color:#2b2b2b; padding:20px; text-align:center; color:#ccc; font-size:13px;">
        <p style="margin:0;">
            Need help? <a href="{{ env('FRONTEND_URL') . '/contact' }}" style="color:#ff7373; text-decoration:none;">Contact our support team</a>.
        </p>
        <p style="margin-top:8px;">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</div>
</body>
</html>
