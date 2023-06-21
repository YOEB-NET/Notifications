<?php

namespace Yoeb\Notifications\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\View;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class YoebMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject, $brief, $image, $mailPrefix, $mailData, $view;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $brief = null, $image = null, $mailPrefix = null, $mailData = [], $view = null)
    {
        $this->subject      = $subject;
        $this->brief        = $brief;
        $this->image        = $image;
        $this->mailPrefix   = $mailPrefix;
        $this->mailData     = $mailData;
        $this->view         = $view;
    }


    public function build()
    {
        $prefix = env("YOEB_MAIL_PREFIX", null);
        if(empty($prefix)){
            $prefix = "";
        }
        if(!empty($this->mailPrefix)){
            $prefix = $this->mailPrefix;
        }
        
        if(empty($this->view)){
            
            if(empty($this->mailData["subject"])){
                $this->mailData["subject"] = $this->subject;
            }

            if(empty($this->mailData["brief"])){
                $this->mailData["brief"] = $this->brief;
            }
            
            if(empty($this->mailData["image"])){
                $this->mailData["image"] = $this->image;
            }
            
            $this->view = realpath(__DIR__ . '\view\mail\base.blade.php');
        }else{
            $this->view = view($this->view)->getPath();
        }

        $mail = $this->subject($prefix . $this->subject);

        $viewContent = View::file($this->view, $this->mailData)->render();

        $mail->html($viewContent);
        return $mail;
    }
}
