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
            color: #333;
        }
        .invoice-container {
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
        }
        .header p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }
        .details {
            margin-bottom: 20px;
        }
        .details p {
            margin: 5px 0;
        }
        .details .label {
            font-weight: bold;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .table th {
            background-color: #f4f4f4;
        }
        .total {
            text-align: right;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
@php
    use Carbon\Carbon;
@endphp

<body>
    <div class="invoice-container">
        <div class="header">
            <h1>Invoice</h1>
            <p>Complex: {{ $invoice->reservation->court->complex->name }}</p>
        </div>

        <div class="details">
            <p><span class="label">Invoice ID:</span> {{ $invoice->id }}</p>
            <p><span class="label">Customer Name:</span> {{ $invoice->customer->name }}</p>
            <p><span class="label">Customer Phone Number:</span> {{ $invoice->customer->customer->phone_number }}</p>
            <p><span class="label">Reservation ID:</span> {{ $invoice->reservation_id }}</p>
            <p><span class="label">Court Name:</span> {{ $invoice->reservation->court->name }}</p>
            <p><span class="label">Court Type:</span> {{ $invoice->reservation->court->court_type->name }}</p>
            <p><span class="label">Date:</span> {{ Carbon::parse($invoice->reservation->reservation_date)->format('d M Y') }}</p>
            <p><span class="label">Due Date:</span> {{ $invoice->due_date ? Carbon::parse($invoice->due_date)->format('d M Y') : 'Not specified' }}</p>
            <p><span class="label">Status:</span> {{ ucfirst($invoice->status) }}</p>
            @if ($invoice->status == 'paid')
                <p><span class="label">Paid At:</span> {{ Carbon::parse($invoice->paid_at)->format('d M Y') }}</p>
            @endif
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Reservation Charges</td>
                    <td>${{ number_format($invoice->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="total">
            <h3>Total: ${{ number_format($invoice->amount, 2) }}</h3>
        </div>

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>If you have any questions about this invoice, please contact us.</p>
        </div>
    </div>
</body>
</html>
