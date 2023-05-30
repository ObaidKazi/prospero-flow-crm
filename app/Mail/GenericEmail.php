<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Company;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;

class GenericEmail extends Mailable
{
    protected Company $company;

    protected ?array $data;
    protected ?User $user;

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $from = isset($this->user) ? $this->user->email : $this->company->email;
        $name = isset($this->user) ? $this->user->getFullName() : $this->company->name;
        return new Envelope(
            from: new Address($from, $name),
            subject: $this->subject,
        );
    }

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(?Company $company = null, ?User $user = null,  ?string $subject = '', ?array $data = [])
    {
        $this->company = $company;
        $this->subject = $subject;
        $this->data = $data;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.generic', $this->data);
    }
}
