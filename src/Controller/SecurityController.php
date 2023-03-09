<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\ResetpwdType;
use App\Form\ResetType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;


class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('home');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

   
    #[Route('/r/forgotpassword', name: 'fp')]
    public function forgotPassword(Request $request, \Swift_Mailer $mailer,EntityManagerInterface $em, TokenGeneratorInterface $tokenGenerator)
    {
        $form = $this->createForm(ResetpwdType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData()['email'];
            $user = $this->getDoctrine()->getRepository(User::class)->findOneByEmail($email);

            if ($user) {
                $token = $tokenGenerator->generateToken();
                $user->setResetToken($token);
                $em->flush();
                $message = (new \Swift_Message('Reset your password'))
                    ->setFrom('nabil.ghazouani@esprit.tn')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'security/reset_pwd.html.twig',
                            [
                                'user' => $user,
                                'token' => $token,
                            ]
                        ),
                        'text/html'
                    );

                $mailer->send($message);
            }

            $this->addFlash('success', 'An email has been sent with instructions to reset your password.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/resetpwd.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    #[Route('/reset-password', name: 'reset_password')]
    public function resetPassword(Request $request,UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager)
    {
        $token = $request->query->get('token');
      
        if (!$token) {
            throw $this->createNotFoundException('Token not found.');
        }

        $user = $entityManager->getRepository(User::class)->findOneBy(['resetToken' => $token]);

    

        $form = $this->createForm(ResetType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pp=$form->get('password')->getData();
            $cc=$form->get('confpassword')->getData();
            
            if ($pp != $cc){
                $this->addFlash('success', 'Your password has been reset.');

            return $this->redirectToRoute('reset_password',['token'=>$token]);
            }
            else {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $user->setResetToken(null);

            $entityManager->flush();

            $this->addFlash('success', 'Your password has been reset.');

            return $this->redirectToRoute('app_login');
        }
        }

        return $this->render('security/reset.html.twig', [
            'f' => $form->createView(),
        ]);
    }
}
