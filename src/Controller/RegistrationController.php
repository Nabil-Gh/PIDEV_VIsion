<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use App\Repository\UserRepository;
use App\Form\ModifuserType;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Form\MedecinType;


class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/sign_up', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setDatecr(new \DateTime("now"));
            $rr=$user->getRoles();
            if ($rr[0]=="ROLE_USER"){
                $user->setIsActivated(1);
                $rr = $user->getRoles();
                $rr[]= 'ROLE_ACTIVE';
            } else {
                $user->setIsActivated(0);
            }
            $brochureFile = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('user_image'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setImage($newFilename); 
            }
           

            $entityManager->persist($user);
            $entityManager->flush();
              // generate a signed url and email it to the user
            
           
            //$this->emailVerifier->sendEmailConfirmation('app_verify_email', $user);
             //do anything else you need here, like send an email
            if ($user->getRoles()[0]=='ROLE_MEDECIN'){
                $idm=$user->getId();
                
                return $this->redirectToRoute('register_med',  ['idm' => $idm]);
            }
          

           

            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('home');
    }
    #[Route('/user/modifier/{{id}}', name: 'modifier_user')]
    public function modifier(Request $request, UserRepository $repository,$id,UserPasswordHasherInterface $userPasswordHasher,SluggerInterface $slugger): Response
    {
        
        
        $user = $repository->find($id);
        $form = $this->createForm(ModifuserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
                
            );
            $brochureFile = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('user_image'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setImage($newFilename); 
            }
            $em= $this->getDoctrine()->getManager();
                $em->flush();
                return $this->redirectToRoute('home');

           
        }
        $user1=$this->getUser();

        return $this->render('user/modifier.html.twig', [
            'registrationForm' => $form->createView(),
            'user' => $user1
            
        ]);
    
    }
    #[Route('/register_med/{idm}', name: 'register_med')]
    public function register_med(Request $request,$idm,UserRepository  $repository ): Response
    {
        
        $med=$repository->find($idm);
        $form = $this->createForm(MedecinType::class, $med);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          
            
          
            

            $em= $this->getDoctrine()->getManager();
            $em->flush();
           
           

            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register_med.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
     
        
    }
    
    
}
