<p>Hello {{ $booking->customer_first_name ?? 'Customer' }},</p>

<p>Your booking has been <strong>confirmed</strong>.</p>

<p>
<strong>Booking #:</strong> {{ $booking->booking_number }}<br>
<strong>Status:</strong> Confirmed
</p>

<p>We look forward to serving you.</p>

<p>— LimoFlux Team</p>
