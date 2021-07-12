<?php


namespace App\Services;


use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class MailerService
{

    private MailerInterface $mailer;

    /**
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param User $user
     * @param string $pass
     * @throws TransportExceptionInterface
     */
    #[Route('/email', name: 'email')]
    public function sendEmail(User $user, string $pass)
    {
        $mail = (new TemplatedEmail())
            ->from('whoblockedme@email.ro')
            ->to($user->getEmail())
            ->subject('Account creation')
            ->htmlTemplate('mail_formats/register_mail.html.twig')
            ->context([
                'mail' => $user->getEmail(),
                'password' => $pass,
            ]);
        $this->mailer->send($mail);
    }
}