<?php


namespace App\Services;


use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Message;
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
    public function sendEmailNewAccount(User $user, string $pass)
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

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/email', name: 'emailblocker')]
    public function sendEmailBlocker(User $blocker, User $blockee, string $lpBlockee, string $lpBlocker){
        $mail = (new TemplatedEmail())
            ->from('whoblockedme@email.ro')
            ->to($blocker->getEmail())
            ->subject('You blocked someone')
            ->htmlTemplate('mail_formats/you_blocked.html.twig')
            ->context([
                'lpBlocker' => $lpBlocker,
                'mail' => $blockee->getEmail(),
                'lpBlockee' => $lpBlockee,
            ]);
        $this->mailer->send($mail);
    }
    public function sendEmailBlockee(User $blockee, User $blocker, string $lpBlockee, string $lpBlocker){
        $mail = (new TemplatedEmail())
            ->from('whoblockedme@email.ro')
            ->to($blockee->getEmail())
            ->subject('You got blocked by someone')
            ->htmlTemplate('mail_formats/you_got_blocked.html.twig')
            ->context([
                'lpBlockee' => $lpBlockee,
                'mail' => $blocker->getEmail(),
                'lpBlocker' => $lpBlocker,
            ]);
        $this->mailer->send($mail);
    }
}