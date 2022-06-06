<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class MyResetPassword extends ResetPassword
{

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Recuperar contraseña - Portal del Empleado')
            ->greeting('Hola.')
            ->line('Estás recibiendo este correo porque hiciste una solicitud de recuperación de contraseña para tu cuenta de Portal del Empleado.')
            ->action('Recuperar contraseña', route('password.reset', $this->token))
            ->line('Si no realizaste esta solicitud, no se requiere realizar ninguna otra acción.')
            ->salutation('Saludos, el equipo de Portal del Empleado.');
            //->salutation('Saludos, '. config('app.name'));
    }
}
