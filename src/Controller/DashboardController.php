<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
class DashboardController extends AbstractController
{


    #[Route('/test', name: 'test')]
    public function test(UserRepository $repository): Response
    {
        $userc=$this->getUser();
        $rr = $userc->getRoles();
        $index = array_search('ROLE_BB', $rr);
        if ($index !== false) {
            array_splice($rr, $index, 1);
        }
        $userc->setRoles($rr);

        $em=$this->getDoctrine()->getManager();
        $em->persist($userc);
        $em->flush();


        
        return $this->render('home/index.html.twig', [
           'user'=>$userc
          
        ]);
    }
    #[Route('/admin/dashboard', name: 'dashboard')]
    public function index(UserRepository $repository): Response
    {
        $userc=$this->getUser();

        
        $user=$repository->findAll();
        return $this->render('dashboard/dash_home.html.twig', [
           'user'=>$user,
           'uc'=>$userc
        ]);
    }
    #[Route('/admin/valider/{id}', name: 'valider_admin')]
    public function valider($id,UserRepository $repository): Response
    {
        $userc=$this->getUser();
        $user1=$repository->find($id);
        $user=$repository->findAll();
        

        
        $user1->setIsActivated(1);
        $em= $this->getDoctrine()->getManager();
        $em->flush();
           
            
           
        

        return $this->render('dashboard/détail.html.twig', [
           'user'=>$user,
           'user1'=>$user1,
           'uc'=>$userc
        ]);
    }
   
}
