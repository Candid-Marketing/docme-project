<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\FolderInvitation;

class GuestFolderInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invitation;

    public function __construct(FolderInvitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function build()
    {
        return $this->subject('You’ve been invited to view a file')
                    ->view('emails.guest_invitations');
    }
}
