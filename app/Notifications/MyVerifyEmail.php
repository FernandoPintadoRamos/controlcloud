<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class MyVerifyEmail extends Notification
{
    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
        }

        return (new MailMessage)
            ->subject(Lang::getFromJson('Verificar cuenta de usuario - Portal del Empleado'))
            ->greeting('Hola.')
            ->line(Lang::getFromJson('Por favor, ingresa en el siguiente enlace para validar su cuenta.'))
            ->action(Lang::getFromJson('Verificar Cuenta de Usuario'), $verificationUrl)
            ->line(Lang::getFromJson('Si no has creado ninguna cuenta, no realice ninguna acción..'))
            ->salutation('Saludos, el equipo de HK Nóminas.');
    }
}
