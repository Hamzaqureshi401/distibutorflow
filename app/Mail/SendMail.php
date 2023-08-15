<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data , $data1 , $blade;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data ,$data1 , $blade)
    {
        $this->data = $data;
    
        $this->data1 = $data1;
        
        $this->blade = $blade;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       // dd($this->data);
        if ($this->blade == "welcome-profit-guru"){
            //dd($this->data);
           return $this->from('dbmail@distributorflow.com' , 'Profit Guru')
           ->subject('Hi, This is Profit Guru Here!')
                     ->markdown('vendor.mail.welcome_profit_guru',['data' => $this->data ]);
        }
        else if ($this->blade == "profit-mail"){
           // dd($this->data);
           return $this->from('dbmail@distributorflow.com' , 'Profit Guru')
           ->subject('Hi, This is Profit Guru Here!')
                     ->markdown('vendor.mail.profit_mail',['data'=>$this->data , 'data1' => $this->data1 ]);
        }
        
        
  
    }
}

?>