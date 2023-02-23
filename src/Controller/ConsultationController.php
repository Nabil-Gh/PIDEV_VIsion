<?php

namespace App\Controller;

use App\Entity\Consultation;
use App\Form\ConsultationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ConsultationRepository;

class ConsultationController extends AbstractController
{
    #[Route('consultation/', name: 'app_consultation')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $data= $doctrine->getRepository(Consultation::class)->findAll();
        return $this->render('consultation/index.html.twig', [
            'list' => $data,
        ]);
    }
    
     #[Route('consultation/add', name: 'consultation_add')]
    public function create(ManagerRegistry $doctrine ,Request $req): Response{
        $em=$doctrine->getManager();
        $cons=new Consultation();
        $form=$this->createForm(ConsultationType::class,$cons);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($cons);
            $em->flush();
            return $this->redirectToRoute('app_consultation');
        }
        
        return $this->renderForm('consultation/create.html.twig',['form'=>$form]);

            
        
    }
    #[Route('consultation/delete/{id}', name: 'consultation_delete')]
    public function remove(ManagerRegistry $doctrine,$id):Response{
        $em= $doctrine->getManager();
        $cons= $doctrine->getRepository(Consultation::class)->find($id);
        $em->remove($cons);
        $em->flush();
        return $this->redirectToRoute('app_consultation');
    }
    #[Route('consultation/update/{id}', name: 'consultation_update')]
    public function update(ManagerRegistry $doctrine,$id,Request $req): Response {
        $em = $doctrine->getManager();
        $cons = $doctrine->getRepository(Consultation::class)->find($id);
        $form = $this->createForm(ConsultationType::class,$cons);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($cons);
            $em->flush();
            return $this->redirectToRoute('app_consultation');
        }
        return $this->renderForm('consultation/create.html.twig',['form'=>$form]);

    }


}
