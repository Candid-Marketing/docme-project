<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .invoice-details {
            margin: 20px 0;
        }
        .invoice-details p {
            font-size: 16px;
            margin: 5px 0;
        }
        .invoice-items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .invoice-items th, .invoice-items td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .invoice-items th {
            background-color: #f2f2f2;
        }
        .total {
            text-align: right;
            font-size: 18px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Invoice #{{ $payment->id }}</h1>
        <div class="invoice-details">
            <p><strong>Customer:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Invoice Date:</strong> {{ \Carbon\Carbon::parse($payment->created_at)->format('F d, Y') }}</p>
            <p><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($user->expiration_date)->format('F d, Y') }}</p>
        </div>

        <h2>Items</h2>
        <table class="invoice-items">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Subscription Plan</td>
                    <td>${{ number_format($payment->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="total">
            <p><strong>Total:</strong> ${{ number_format($payment->amount, 2) }}</p>
        </div>

        <p>Thank you for your business!</p>
    </div>
</body>
</html>
