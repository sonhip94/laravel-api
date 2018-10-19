<?php

namespace App\Mail\User;

use App\Mail\BaseMail;
use App\Services\Helpers\EmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\View;

class WelcomeMail extends BaseMail
{
    use Queueable, SerializesModels;

    protected $viewName = 'user.welcome';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->data = ['user' => $user];
        parent::__construct();
    }
}
