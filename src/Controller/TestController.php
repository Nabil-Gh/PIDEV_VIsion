<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;




class TestController extends AbstractController
{
  

   
    #[Route('/testmm', name: 'app_test')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        
       

        return $this->render('registration/confirmation_email.html.twig', [
            
            
        ]);
    }
    
}
