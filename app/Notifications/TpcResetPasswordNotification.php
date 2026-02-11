<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Symfony\Component\Mime\Email;

class TpcResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $email = $notifiable->getEmailForPasswordReset();

        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $email,
        ], false));

        $logoPath = public_path('images/TPC-Logo.png');

        return (new MailMessage)
            ->subject('Reset Password Notification')
            ->withSymfonyMessage(function (Email $message) use ($url, $logoPath) {

                $hasLogo = is_file($logoPath);
                $logoCid = 'tpc-logo'; // <- you choose this name

                // Embed logo as inline attachment (CID)
                if ($hasLogo) {
                    // 2nd param is the CID name
                    $message->embedFromPath($logoPath, $logoCid, 'image/png');
                }

                $html = view('emails.reset-password', [
                    'url' => $url,
                    'hasLogo' => $hasLogo,
                    'logoCid' => $logoCid, // use as cid:tpc-logo
                    'appName' => 'Talibon Polytechnic College',
                    'signature' => 'SecrIT Admin',
                ])->render();

                $text =
"Talibon Polytechnic College
{$url}

Hello!

You are receiving this email because we received a password reset request for your account.

Reset Password: {$url}

This password reset link will expire in 60 minutes.

If you did not request a password reset, no further action is required.

Regards,
SecrIT Admin
";

                $message->html($html);
                $message->text($text);
            });
    }
}
