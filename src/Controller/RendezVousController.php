<?php

namespace App\Controller;
use App\Form\UpdateType;
use App\Form\RendezVousType;
use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\RendezVous;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormTypeInterface;
use App\Repository\RendezVousRepository;
use App\Repository\UserRepository;

class RendezVousController extends AbstractController
{

    #[Route('/rendez_vous/afficherback', name: 'afficherrv')]
    public function afficherback(RendezVousRepository $repo,UserRepository $rp): Response
    {

        $rendezvous = $repo->findAll();
        return $this->render('rendez_vous/afficherrv.html.twig', [
            'rdv' => $rendezvous,
        ]);
    }

    #[Route('/rendez_vous/addfront', name: 'add_rv')]
    public function addfront(ManagerRegistry $doctrine,Request $request,UserRepository $rp): Response
    {
        $user=$rp->find(31);
        $med=$rp->find(11);
        $em = $doctrine->getManager();
        $rendezvous = new RendezVous();
        $form = $this->createForm(RendezVousType::class, $rendezvous);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $rendezvous-> setPatient($user);
            $rendezvous-> setMed($med);
            $em->persist($rendezvous);
            $em->flush();
            return $this->redirectToRoute('afficherfr');
        }
        return $this->renderForm('rendez_vous/appointmentform.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/rendez_vous/update/{id}', name: 'update_rv')]
    public function update(ManagerRegistry $doctrine,Request $request,$id): Response
    {
        $em = $doctrine->getManager();
        $rendezvous = $doctrine->getRepository(RendezVous::class)->find($id);
        $form = $this->createForm(UpdateType::class, $rendezvous);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
          
            $em->flush();
            return $this->redirectToRoute('afficherrv');
        }
        return $this->renderForm('rendez_vous/update.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('rendez_vous/remove/{id}', name: 'removerv')]
    public function removeback(ManagerRegistry $doctrine,$id): Response
    {
        $em= $doctrine->getManager();
        $rendezvous= $doctrine->getRepository(RendezVous::class)->find($id);
        $em->remove($rendezvous);
        $em->flush();
        return $this->redirectToRoute('afficherrv');
    }


    #[Route('rendez_vous/afficherfront', name: 'afficherfr')]
    public function afficherfront(RendezVousRepository $repo): Response
    {
        $rendezvous = $repo->findAll();
        return $this->render('rendez_vous/afficherrvfront.html.twig', [
            'rdv' => $rendezvous,
        ]);
    }

    #[Route('/rendez_vous/updatefront/{id}', name: 'updatefr')]
    public function updatefront(ManagerRegistry $doctrine,Request $request,$id): Response
    {
        $em = $doctrine->getManager();
        $rendezvous = $doctrine->getRepository(RendezVous::class)->find($id);
        $form = $this->createForm(UpdateType::class, $rendezvous);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $em->flush();
            return $this->redirectToRoute('afficherfr');
        }
        return $this->renderForm('rendez_vous/updatefront.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('rendez_vous/removefr/{id}', name: 'removefr')]
    public function removefront(ManagerRegistry $doctrine,$id): Response
    {
        $em= $doctrine->getManager();
        $rendezvous= $doctrine->getRepository(RendezVous::class)->find($id);
        $em->remove($rendezvous);
        $em->flush();
        return $this->redirectToRoute('afficherfr');
    }



}
