<?php

namespace App\Controller;

use App\Repository\ReclamationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UserRepository;

class ReclamationController extends AbstractController
{
#[Route('/reclamation_ajout', name: 'reclamation_ajout')]
    public function ajout_reclamation(Request $request,UserRepository $rp): Response
    {
        $user=$rp->find(7);
        $reclamation=new Reclamation();
        $form=$this->createForm(ReclamationType::class,$reclamation);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $reclamation->setEtat("en attente");
            $reclamation->setUser($user);
            $reclamation->setDate(new \DateTime("now"));
            $em->persist($reclamation);
            $em->flush();
            return $this->redirectToRoute("afficher_reclamation");

        }

      
        return $this->render('reclamation/ajout_reclamation.html.twig', [
            'f' => $form->createView(),
        ]);
       
    }
    

    #[Route('/afficher_reclamation', name: 'afficher_reclamation')]
    public function afficher_reclamation(ReclamationRepository $repository): Response
    {

        
        $reclamation=$repository->findAll();
     
        return $this->render('reclamation/afficher_reclamation.html.twig',[
            'rec' => $reclamation,
            
         
        ] );
    }
    #[Route('reclamation/remove/{id}', name: 'remover')]
    public function removeback(ManagerRegistry $doctrine,$id): Response
    {
        $em= $doctrine->getManager();
        $Reclamation= $doctrine->getRepository(Reclamation::class)->find($id);
        $em->remove($Reclamation);
        $em->flush();
        return $this->redirectToRoute('afficher_reclamation');
    }
    
    
    #[Route('/f/afficher_reclamation2', name: 'afficher_reclamation_user')]
    public function afficher_reclamation_user(ReclamationRepository $repository,UserRepository $urp): Response
    {

        $user=$urp->find(7);
        $reclamation=$repository->findByuser($user);
     
        return $this->render('reclamation/afficher2_reclamation.html.twig',[
            'rec' => $reclamation,
            
         
        ] );
    }
}
