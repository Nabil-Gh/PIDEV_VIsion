<?php

namespace App\Controller; 

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use App\Entity\Specialite;
use App\Form\SpecialiteType;
use App\Repository\SpecialiteRepository;
class UserController extends AbstractController
{
    #[Route('/admin/afficher_user', name: 'afficher_user')]
    public function afficher_user(UserRepository $repository): Response
    {

        $userc=$this->getUser();
       $user=$repository->findAll();
     
        return $this->render('user/afficher_user.html.twig',[
            'user' => $user,
            'uc'=>$userc
         
        ] );
    }
    #[Route('/admin/user/supprimer/{id}', name: 'supprimer_user')]
    public function supprimer_user(UserRepository  $repository , $id,Request $request)
    {
        $user=$repository->find($id);
        
        $em=$this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('afficher_user');
       
    }
    #[Route('/admin/afficher_med', name: 'afficher_med')]
    public function afficher_med(UserRepository $repository): Response
    {

        $userc=$this->getUser();
        $user=$repository->findAll();
     
        return $this->render('user/afficher_med.html.twig',[
            'user' => $user,
            'uc'=>$userc
         
        ] );
    }
    #[Route('/admin/afficher_pharm', name: 'afficher_pharm')]
    public function afficher_pharm(UserRepository $repository): Response
    {

        $userc=$this->getUser();
        $user=$repository->findAll();
     
        return $this->render('user/afficher_pharm.html.twig',[
            'user' => $user,
            'uc'=>$userc
         
        ] );
    }
    #[Route('/admin/detail_user/{id}', name: 'detail_user')]
    public function detail_user($id,UserRepository $repository): Response
    {

        $userc=$this->getUser();
        $u=$repository->find($id);
        $user=$repository->findAll();
     
        return $this->render('user/detail_user.html.twig',[
            'user' => $user,
            'uc'=>$userc,
            'user1'=>$u
         
        ] );
    }
    #[Route('/admin/user/bloquer/{id}', name: 'bloquer_user')]
    public function bloquer_user(UserRepository  $repository , $id,Request $request, \Swift_Mailer $mailer)
    {
        $user=$repository->find($id);
        $user->setIsActivated(0);
        $rr = $user->getRoles();
        $index = array_search('ROLE_ACTIVE', $rr);
        if ($index !== false) {
            array_splice($rr, $index, 1);
        }
        $user->setRoles($rr);
        $em=$this->getDoctrine()->getManager();
        $message = (new \Swift_Message('DoCare'))
        ->setFrom('nabil.ghazouani@esprit.tn')
        ->setTo($user->getEmail())
        ->setBody(
            "Mr " . $user->getPrenom(). $user->getNom() . " , Votre email a été bloqué "
        );
        //$mailer->send($message);
        $em->flush();
        return $this->redirectToRoute('dashboard');
       
    }
    #[Route('/admin/user/activer/{id}', name: 'activer_user')]
    public function activer_user(UserRepository  $repository , $id,Request $request, \Swift_Mailer $mailer)
    {
        $user=$repository->find($id);
        $user->setIsActivated(1);
        $rr = $user->getRoles();
        $rr[]= 'ROLE_ACTIVE';
        $user->setRoles($rr);
        $em=$this->getDoctrine()->getManager();
        $message = (new \Swift_Message('DoCare'))
        ->setFrom('nabil.ghazouani@esprit.tn')
        ->setTo($user->getEmail())
        ->setBody(
            "Mr " . $user->getPrenom(). $user->getNom() . " , Votre email a été activé avec succée"
        );

        //$mailer->send($message);
        
        $em->flush();
        return $this->redirectToRoute('dashboard');
       
    }
    #[Route('/admin/ajout_specialite', name: 'ajout_specialite')]
    public function ajout_specialite(Request $request,UserRepository  $repository ): Response
    {
        $user=$repository->findAll();
        $userc=$this->getUser();
        $spec = new Specialite();
        $form = $this->createForm(SpecialiteType::class, $spec);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          
            
          
            

            $em= $this->getDoctrine()->getManager();
            $em->persist($spec);
            $em->flush();
           
           

            return $this->redirectToRoute('afficher_specialite');
        }

        return $this->render('user/ajout_specialite.html.twig', [
            'f' => $form->createView(),
            'user'=>$user,
            'uc'=>$userc
        ]);
     
        
    }
    #[Route('/admin/specialite/supprimer/{id}', name: 'supprimer_specialite')]
    public function supprimer_specialite(SpecialiteRepository  $rp , $id,Request $request)
    {
        $spec=$rp->find($id);
        
        $em=$this->getDoctrine()->getManager();
        $em->remove($spec);
        $em->flush();
        return $this->redirectToRoute('afficher_specialite');
       
    }
    #[Route('/admin/afficher_specialite', name: 'afficher_specialite')]
    public function afficher_specialite(SpecialiteRepository  $rp ,UserRepository $repository): Response
    {

        $userc=$this->getUser();
        $user=$repository->findAll();
        $spec=$rp->findAll();
     
        return $this->render('user/afficher_specialite.html.twig',[
            'user' => $user,
            'uc'=>$userc,
            'spec'=>$spec
         
        ] );
    }
}

