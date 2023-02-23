<?php

namespace App\Controller;

use App\Entity\Consultation;
use App\Entity\Ordonnance;
use App\Form\OrdonnanceType;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ConsultationRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrdonnanceController extends AbstractController
{
    #[Route('/ordonnance', name: 'app_ordonnance')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $data= $doctrine->getRepository(Ordonnance::class)->findAll();
        return $this->render('ordonnance/index.html.twig', [
            'list' => $data,
        ]);
    }
    #[Route('/ordo', name: 'app_ordo')]
    public function index2(): Response
    {
        
        return $this->render('ordonnance/patientOrdo.html.twig', [
            
        ]);
    }
    #[Route('ordonnance/add', name:'ordonnance_add')]
     
    public function create(ManagerRegistry $doctrine ,Request $req,ConsultationRepository $r): Response{
        $em=$doctrine->getManager();
        #$cons=$r->find(9);
        $ord=new Ordonnance();
        $form=$this->createForm(OrdonnanceType::class,$ord);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            #$ord->setConsultation($cons);
            $em->persist($ord);
            $em->flush();
            return $this->redirectToRoute('app_ordonnance');
        }
        
        return $this->renderForm('ordonnance/create.html.twig',['form'=>$form]);

            
        
    }

}
