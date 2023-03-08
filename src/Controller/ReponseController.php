<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Reclamation;
use App\Form\ReponseformType;
use App\Entity\Reponse;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ReclamationRepository;
use App\Repository\ReponseRepository;
use Symfony\Component\Routing\Annotation\Route;

class ReponseController extends AbstractController
{

#[Route('/reponse_ajout/{id}', name: 'reponseajout')]
    public function ajoutrep(Request $request,ReclamationRepository $rp,$id,ManagerRegistry $doctrine): Response
    {
        $em= $doctrine->getManager();
        $rec= $rp->findAll();
        $reclamation= $doctrine->getRepository(Reclamation::class)->find($id);
        $reponse=new Reponse();
        $form=$this->createForm(ReponseformType::class,$reponse);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {

            $reponse->setDateCreation(new \DateTime("now"));
            $reponse->setIdRec($reclamation);
            $reclamation->setEtat("traite");
            $em->persist($reponse);
            $em->flush();
           


            return $this->redirectToRoute("afficher_reclamation");

        }

      
        return $this->render('reclamation/reponse.html.twig', [
            'f' => $form->createView(),
            'rec'=>$rec
        ]);
    }


    #[Route('/afficherrep/{id}', name: 'afficherrep')]
    public function afficherrep($id,ReclamationRepository $rrp , ReponseRepository $repo): Response
    {
        $reclam=$rrp->find($id);
        $reponse=$repo->reprec($reclam->getId());
        
        
     
        return $this->render('reponse/afficherrepf.html.twig',[
            'rec' => $reponse[0],
            
         
        ] );
    }
}
