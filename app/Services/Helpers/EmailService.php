<?php

namespace App\Services\Helpers;

use Illuminate\Support\Facades\Mail;

class EmailService
{
    public static function send($mail, $emailTo, $cc = [], $bcc = [])
    {
        \Log::info('sendmail');
        Mail::to($emailTo)
            ->cc($cc)
            ->bcc($bcc)
            ->send($mail);
    }
    public static function getLayoutPath($layout)
    {
        $lang = config('app.locale');
        $layoutPath = 'emails.' . $lang . '.' . $layout;

        return $layoutPath;
    }
}
