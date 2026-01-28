<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Successful - {{ config('app.name') }}</title>
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
            background-color: #198754;
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

        .email-body p {
            font-size: 15px;
            line-height: 1.7;
            margin: 10px 0;
        }

        .order-details {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .order-details th,
        .order-details td {
            border: 1px solid #dee2e6;
            padding: 10px;
            font-size: 14px;
            text-align: left;
        }

        .order-details th {
            background-color: #f1f3f5;
            color: #333;
        }

        .order-summary {
            text-align: right;
            margin-top: 20px;
            font-size: 16px;
        }

        .btn {
            display: inline-block;
            background-color: #198754;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 16px;
            transition: background 0.3s;
        }

        .btn:hover {
            background-color: #157347;
        }

        .btn-container {
            text-align: center;
            margin: 30px 0;
        }

        .email-footer {
            background-color: #198754;
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

            .order-details th,
            .order-details td {
                font-size: 13px;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
<div class="email-container">

    <!-- Header -->
    <div class="email-header">
        <h2>🎉 Order Confirmed!</h2>
    </div>

    <!-- Body -->
    <div class="email-body">
        <p>Hi {{ $userName }},</p>
        <p>Thank you for shopping with <strong>{{ config('app.name') }}</strong>! Your order has been placed successfully and is now being processed.</p>

        <h4 style="margin-top:25px; color:#198754;">Order Details</h4>
        <table class="order-details">
            <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price ($)</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="order-summary">
            <p><strong>Total:</strong> ৳{{ number_format($order->order_total, 2) }}</p>
        </div>

        <p style="margin-top:25px;">You can track your order anytime from your account dashboard.</p>

        <div class="btn-container">
            <a href="{{ env('FRONTEND_URL') . '/dashboard' }}" class="btn">View My Order</a>
        </div>
    </div>

    <!-- Footer -->
    <div class="email-footer">
        © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </div>
</div>
</body>
</html>
{{--৳--}}
