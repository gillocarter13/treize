<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    public $temporaryPassword;

    public function __construct($temporaryPassword)
    {
        $this->temporaryPassword = $temporaryPassword;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Réinitialisation de votre mot de passe')
            ->greeting('Bonjour,')
            ->line('Votre mot de passe temporaire est : ' . $this->temporaryPassword)
            ->line('Veuillez utiliser ce mot de passe pour vous connecter et le modifier immédiatement dans les paramètres de votre compte.')
            ->salutation('Merci de votre confiance.');
    }
}
