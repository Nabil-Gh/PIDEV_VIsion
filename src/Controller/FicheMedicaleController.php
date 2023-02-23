<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Repository\FicheMedicaleRepository;
use App\Repository\DocumentsRepository;

class FicheMedicaleController extends AbstractController
{
    #[Route('/f/fiche/ficheback', name: 'afficheffr')]
    public function afficherback(FicheMedicaleRepository $repo,UserRepository $rp,DocumentsRepository $rep): Response
    {

        $user=$rp->find(31);
        $fichemedicale = $repo->findByuser($user);
        $docs=$rep->findByfiche($fichemedicale[0]);
        return $this->render('fiche_medicale/ficheMedicale.html.twig', [
            'fiche' => $fichemedicale[0],'user'=>$user,'docs'=>$docs,
        ]);
    }


}