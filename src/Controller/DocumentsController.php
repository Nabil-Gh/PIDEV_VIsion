<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\UpdatedocType;
use App\Form\DocumentsType;
use App\Repository\UserRepository;
use App\Repository\FicheMedicaleRepository;
use App\Repository\DocumentsRepository;
use App\Entity\Documents;
use Symfony\Component\HttpFoundation\Request;


class DocumentsController extends AbstractController
{  #[Route('/f/docs/ajouterdoc', name: 'ajouterdoc')]
    public function ajouterdoc(FicheMedicaleRepository $repo,UserRepository $rp,ManagerRegistry $doctrine,Request $request,SluggerInterface $slugger): Response
    {
        $em = $doctrine->getManager();
        $Doc = new Documents();
        $user=$rp->find(31);
        $fichemedicale = $repo->findByuser($user);
        $form = $this->createForm(DocumentsType::class, $Doc);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $brochureFile=$form->get('fichier')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

               
                try {
                    $brochureFile->move(
                        $this->getParameter('Doc_File'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    
                }

               
                $Doc->setFichier($newFilename); 
            }
            $Doc->setFiche($fichemedicale[0]);
            $em->persist($Doc);
            $em->flush();
            return $this->redirectToRoute('afficherdfr');
        }
        return $this->renderForm('Documents/Ajoutdoc.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/f/docs/afficherfront', name: 'afficherdfr')]
    public function afficherfront(DocumentsRepository $repo,UserRepository $rp,FicheMedicaleRepository $rep): Response
    {  
        $user=$rp->find(31);
        $fichemedicale=$rep->findByuser($user);
        
        $Docs = $repo->findbyFiche($fichemedicale[0]);
        
        return $this->render('documents/afficherdoc.html.twig', [
            'docs' => $Docs,'fiche'=>$fichemedicale[0],
        ]);
    }


    #[Route('/f/docs/deletedoc/{id}', name: 'removed')]
    public function removeback(ManagerRegistry $doctrine,$id): Response
    {
        $em= $doctrine->getManager();
        $doc= $doctrine->getRepository(Documents::class)->find($id);
        $em->remove($doc);
        $em->flush();
        return $this->redirectToRoute('afficherdfr');
    }

    #[Route('/f/docs/updatedoc/{id}', name: 'modifd')]
    public function update(ManagerRegistry $doctrine,Request $request,$id): Response
    {
        $em = $doctrine->getManager();
        $doc = $doctrine->getRepository(Documents::class)->find($id);
        $form = $this->createForm(UpdatedocType::class, $doc);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('afficherdfr');
        }
        return $this->renderForm('documents/updatedoc.html.twig', [
            'form' => $form,
        ]);
    }


}
