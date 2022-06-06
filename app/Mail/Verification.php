<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Verification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return (new MailMessage)
        ->subject(Lang::getFromJson('Verificar cuenta de usuario - Portal del Empleado'))
        ->greeting('Hola.')
        ->line(Lang::getFromJson('Por favor, ingresa en el siguiente enlace para validar su cuenta.'))
        ->action(Lang::getFromJson('Verificar Cuenta de Usuario'), $verificationUrl)
        ->line(Lang::getFromJson('Si no has creado ninguna cuenta, no realice ninguna acción..'))
        ->salutation('Saludos, el equipo de HK Nóminas.');
    }
}
