<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <link href="{{ asset('imgs/icon.png') }}" rel="icon">
    <link href="{{ asset('imgs/docme_logo.png') }}" rel="apple-touch-icon">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            background: linear-gradient(135deg, #3abfdd, #3b68b2);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            background: white;
            border-radius: 20px;
            max-width: 600px;
            width: 90%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .logo {
            display: block;
            margin: 0 auto 10px auto;
            max-width: 180px;
            height: auto;
        }

        h2 {
            margin: 0 0 30px 0;
            font-size: 1.75rem;
            font-weight: 600;
            color: #333;
            text-align: left;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        td {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            font-size: 15px;
        }

        td:first-child {
            font-weight: 600;
            width: 40%;
            color: #333;
        }

        .actions {
            text-align: center;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            margin: 5px 10px;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
            color: white;
        }

        .btn-dashboard {
            background-color: #3b68b2;
        }

        .btn-login {
            background-color: #683695;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #999;
            margin-top: 10px;
        }

        @media (max-width: 500px) {
            h2 {
                font-size: 1.5rem;
            }

            td {
                font-size: 14px;
            }

            .btn {
                font-size: 13px;
                padding: 8px 16px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Logo -->
        <img src="{{ asset('imgs/docme_logo.png') }}" alt="DocME Logo" class="logo">

        <!-- Heading -->
        <h2>Payment Receipt</h2>

        <!-- Receipt Details -->
        <table>
            <tr>
                <td>Receipt No:</td>
                <td>{{ $payment->id }}</td>
            </tr>
            <tr>
                <td>Name:</td>
                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
            </tr>
            <tr>
                <td>Email:</td>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <td>Amount Paid:</td>
                <td>${{ number_format($payment->amount, 2) }}</td>
            </tr>
            <tr>
                <td>Currency:</td>
                <td>{{ strtoupper($payment->currency) }}</td>
            </tr>
            <tr>
                <td>Payment Status:</td>
                <td>{{ ucfirst($payment->status) }}</td>
            </tr>
            <tr>
                <td>Payment Date:</td>
                <td>{{ $payment->created_at->format('F j, Y, g:i A') }}</td>
            </tr>
        </table>

        <!-- Centered Buttons -->
        <div class="actions">
            <a href="{{ route('stripe.dashboard') }}" class="btn btn-dashboard">Proceed to Dashboard</a>
            <a href="{{ route('stripe.login') }}" class="btn btn-login">Proceed to Login</a>
        </div>

        <!-- Footer -->
        <div class="footer">
            &copy; {{ date('Y') }} Candid Marketing Inc. All rights reserved.
        </div>
    </div>

</body>
</html>
