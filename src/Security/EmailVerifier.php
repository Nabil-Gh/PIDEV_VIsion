<?php

namespace App\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use Swift_Message;
use Twig\Environment;
class EmailVerifier
{
    public function __construct(
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private \Swift_Mailer $mailer ,
        private EntityManagerInterface $entityManager,
        private \Twig\Environment $twig
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendEmailConfirmation(string $verifyEmailRouteName, UserInterface $user): void
    {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            $user->getId(),
            $user->getEmail()
        );

        //$context = $email->getContext();
        //$context['signedUrl'] = $signatureComponents->getSignedUrl();
        //$context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        //$context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();
        $template = $this->twig->load('registration/confirmation_email.html.twig');
        $body=$template->render(
            
            ['signedUrl' => $signatureComponents->getSignedUrl(),
            'expiresAtMessageKey'=>  $signatureComponents->getExpirationMessageKey(),
            'expiresAtMessageData'=>  $signatureComponents->getExpirationMessageData()
            ]);
        $message = (new Swift_Message('Confirm your email address'))
        ->setFrom('nabil.ghazouani@esprit.tn' )
        ->setTo($user->getEmail())
        ->setBody($body, 'text/html');
    

        
       
        $this->mailer->send($message);
    }

    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(Request $request, UserInterface $user): void
    {
        $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());

        $user->setIsVerified(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
