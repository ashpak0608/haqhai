<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailOtp extends Mailable
{
    use Queueable, SerializesModels;

    public $dear_name;
    public $otp;

    protected $subjectLine;
    protected $mail_to;

    public function __construct($otp, $subject = null, $mail_to, $dear_name)
    {
        $this->otp = $otp;
        $this->subjectLine = $subject ?? 'Ajakin Registration OTP';
        $this->mail_to = $mail_to;
        $this->dear_name = $dear_name;
    }

    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->to($this->mail_to)
                    ->subject($this->subjectLine)
                    ->view('email.registration_otp');
    }
}
