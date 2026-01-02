<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $booking->booking_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { font-size: 20px; }
        table { width: 100%; border-collapse: collapse; }
        td, th { padding: 8px; border-bottom: 1px solid #ddd; }
    </style>
</head>
<body>

<h1>LimoFlux Invoice</h1>

<p>
    <strong>Booking Ref:</strong> {{ $booking->booking_number }}<br>
    <strong>Date:</strong> {{ $booking->created_at->format('d M Y') }}<br>
    <strong>Status:</strong> {{ ucfirst($booking->payment_status) }}
</p>

<h3>Customer</h3>
<p>
    {{ $booking->customer_first_name }} {{ $booking->customer_last_name }}<br>
    {{ $booking->customer_email }}
</p>

<h3>Trip</h3>
<p>
    {{ $booking->pickup_address }} → {{ $booking->dropoff_address }}
</p>

<h3>Amount</h3>
<table>
    <tr>
        <th align="left">Subtotal</th>
        <td>{{ $booking->currency }} {{ number_format($booking->subtotal, 2) }}</td>
    </tr>
    <tr>
        <th align="left">Tax</th>
        <td>{{ $booking->currency }} {{ number_format($booking->tax, 2) }}</td>
    </tr>
    <tr>
        <th align="left">Total</th>
        <td><strong>{{ $booking->currency }} {{ number_format($booking->total, 2) }}</strong></td>
    </tr>
</table>

<p>Thank you for choosing LimoFlux.</p>

</body>
</html>
