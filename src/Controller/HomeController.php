<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;


class HomeController extends AbstractController 
{
    #[Route('/home', name: 'home')]
    public function home(): Response
    {

        $user1=$this->getUser();
       
       
      
       if ($user1){
        $x=$user1->getRoles();
        if($x[0]=="ROLE_ADMIN"){
            
            return $this->redirectToRoute("dashboard");
        }
       }
        return $this->render('home/index.html.twig', [
            'user'=>$user1
            
        ]);
    }
   
    #[Route('/f/afficher_med_front', name: 'afficher_med_front')]
    public function afficher_med_front(UserRepository $repository): Response
    {

        $user=$this->getUser();
        $med=$repository->findAll();
     
        return $this->render('home/afficher_med_front.html.twig',[
            'user' => $user,
            'med'=> $med
            
         
        ] );
    }
    #[Route('/f/afficher_pharm_front', name: 'afficher_pharm_front')]
    public function afficher_pharm_front(UserRepository $repository): Response
    {

        $user=$this->getUser();
        $med=$repository->findAll();
     
        return $this->render('home/afficher_pharm_front.html.twig',[
            'user' => $user,
            'med'=> $med
            
         
        ] );
    }
 
}
