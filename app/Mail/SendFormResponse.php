<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
// use Illuminate\Bus\Queueable;
// use Illuminate\Mail\Mailable;
// use Illuminate\Queue\SerializesModels;

class SendFormResponse extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $formname;
    public $data;
       public $em;
       public $formem;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($formname,$data,$em = null,$fem )
    {
                   $this->formname = $formname;
                    $this->data = $data;
                     $this->em = $em;
                      $this->formem = $fem;
                  
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   

        return $this->from($this->formem,$this->formname)->view('email.form_response')->with('data', $this->data)->subject('Inqury Form '. $this->formname);
    }
}
