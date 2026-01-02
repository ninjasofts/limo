<p>Hello {{ $booking->customer_first_name ?? 'Customer' }},</p>

<p>Your booking has been <strong>cancelled</strong>.</p>

<p>
<strong>Booking #:</strong> {{ $booking->booking_number }}
</p>

<p>If you have any questions, please contact us.</p>

<p>— LimoFlux Team</p>
