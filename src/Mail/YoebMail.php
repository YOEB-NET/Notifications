<?php

namespace Yoeb\Notifications\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class YoebMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $subject, $mailData, $view;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $mailData = [], $view = null)
    {
        $this->subject      = $subject;
        $this->mailData     = $mailData;
        $this->view         = $view;
    }


    public function build()
    {
        $prefix = env("YOEB_MAIL_PREFIX", null);
        if(!empty($prefix)){
            $prefix = $prefix . " - ";
        }

        if(empty($this->view)){
            $this->view = "";
        }

        $mail = $this->subject($prefix . $this->subject)->view(__DIR__.'/../view/mail/base');
        return $mail;
        //return $this->subject($prefix . $this->subject)->view(__DIR__.'/../view/mail/base')->with("code", $this->mailData["code"]);
    }
}
