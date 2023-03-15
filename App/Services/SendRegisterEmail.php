<?php

namespace App\Services;

use App\Exceptions\FailedSendEmail;
use App\Models\User;
use Core\Abstractions\Service;
use Core\Helpers\Mailer;
use Core\Helpers\Uri;
use PHPMailer\PHPMailer\Exception;

class SendRegisterEmail extends Service
{
    /**
     * @throws Exception
     * @throws FailedSendEmail
     */
    public static function send(User $user): bool
    {
        $mail = new Mailer(0);

        try {
            $mail->mount(
                'Vortex Automatic Register Email',
                Uri::getViewPath('/mail/body.php'),
                true,
                true)
                ->make($user->email, $user->name, $_ENV['MAIL_USERNAME'])
                ->withEmbeddedImage(
                    [
                        'vortex_logo' => Uri::getPublicRealPath('img/vortex.png'),
                        'register_email' => Uri::getPublicRealPath('img/register_email.png'),
                    ]
                )
                ->send();
            return true;
        } catch (FailedSendEmail $exception) {
            throw new FailedSendEmail($user->email);
        }
    }
}
