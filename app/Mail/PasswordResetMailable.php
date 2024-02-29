<?php

namespace App\Mail;

use App\Models\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PasswordResetMailable extends Mailable
{
    use Queueable, SerializesModels;

    private $data;

    public function __construct($user)
    {
        $this->data = ['user' => $user];
    }

    public function build()
    {
        $from = config('mail.from.address', 'hello@banglafire.com');
        $name = $this->data['user']->company->group->name ?? 'Banglafire Software Ltd.';

        return $this
            ->subject('Reset Your Password')
            ->from($from, $name)
            ->view('mails.password-reset', $this->data);
    }
}
