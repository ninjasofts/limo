<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingCreated extends Mailable
{
    use SerializesModels;

    public function __construct(public Booking $booking) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Received – ' . $this->booking->booking_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking.created',
            with: ['booking' => $this->booking],
        );
    }
}
