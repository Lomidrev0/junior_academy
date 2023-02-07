<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MessageMail extends Mailable
{
    use Queueable, SerializesModels;
    private $message;
    private $meida;
    private $fromAdd;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($message,$media,$fromAdd)
    {
        $this->message = $message;
        $this->meida = $media;
        $this->fromAdd = $fromAdd;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this
            ->markdown('emails.message')
            ->subject($this->message->subject)
            ->with('message', $this->message)
            ->from($this->fromAdd);
        foreach ($this->meida as $file){
            $this->attach(public_path("storage/$file->disk/$file->id/$file->file_name"));
        }
        return $this;
    }
}
