<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmed extends Mailable
{
    use SerializesModels;

    public function __construct(public Booking $booking) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Confirmed – ' . $this->booking->booking_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking.confirmed',
            with: ['booking' => $this->booking],
        );
    }
}
