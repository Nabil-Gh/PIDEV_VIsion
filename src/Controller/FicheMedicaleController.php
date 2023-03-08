<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Repository\FicheMedicaleRepository;
use App\Repository\DocumentsRepository;
use App\Repository\RendezVousRepository;

class FicheMedicaleController extends AbstractController
{
    #[Route('/f/fiche/ficheback/{id}', name: 'afficheffr')]
    public function afficherback(FicheMedicaleRepository $repo,UserRepository $rp,DocumentsRepository $rep,$id,RendezVousRepository $rv): Response
    {
        $rendezvous=$rv->find($id);
        $med=$rp->find(23);
        $user=$rendezvous->getPatient();
        $rdv=$rv->findAll();
        $khlifa=$rv->findAll();
        
        $fichemedicale = $repo->findByuser($user);
        
        $docs=$rep->findByfiche($fichemedicale[0]);
        
        
        return $this->render('fiche_medicale/ficheMedicale.html.twig', [
            'fiche' => $fichemedicale[0],'user'=>$user,'docs'=>$docs,'rdv'=>$rdv,'khlifa'=>$khlifa,'med'=>$med
        ]);
    }


}