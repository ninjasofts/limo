<p>Hello {{ $booking->customer_first_name ?? 'Customer' }},</p>

<p>Your booking has been received successfully.</p>

<p>
<strong>Booking #:</strong> {{ $booking->booking_number }}<br>
<strong>Status:</strong> Pending confirmation
</p>

<p>We will confirm your booking shortly.</p>

<p>— LimoFlux Team</p>
