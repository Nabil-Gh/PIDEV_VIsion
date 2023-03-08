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
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Snappy\Pdf;

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
    #[Route('/f/afficher_reclamation/nontraité', name: 'afficher_reclamation_user_nontraité')]
    public function afficher_reclamation_user_nontraité(ReclamationRepository $repository,UserRepository $urp): Response
    {
        $rec=$repository->findAll();
        $recs=$repository->findbynontraite("en attente");
        return $this->render('reclamation/nontraité.html.twig',[
            'rec' => $rec,
            'recs'=>$recs
            
         
        ] );

    }
    #[Route('/f/afficher_reclamation/traité', name: 'afficher_reclamation_user_traité')]
    public function afficher_reclamation_user_traité(ReclamationRepository $repository,UserRepository $urp): Response
    {
        $rec=$repository->findAll();
        $recs=$repository->findbynontraite("traite");
        return $this->render('reclamation/traité.html.twig',[
            'rec' => $rec,
            'recs'=>$recs
            
         
        ] );

    }



    /**
     * @Route ("/Imprimer/{id}" ,name="imp")
     */

     public function pdf($id,ReclamationRepository $repository)
     {
         // Configure Dompdf according to your needs
         $pdfOptions = new Options();
         $pdfOptions->set('defaultFont', 'Arial');
 
         // Instantiate Dompdf with our options
         $dompdf = new Dompdf($pdfOptions);
         $reclamation=$repository->find($id);
 
         $html = $this->renderView('reclamation/imp_pdf.html.twig',
             ['reclamation' => $reclamation
             ]);
         // Load HTML to Dompdf
         $dompdf->loadHtml($html);
 
         // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
         $dompdf->setPaper('A4', 'portrait');
 
 
         // Render the HTML as PDF
         $dompdf->render();
         $pdfFilePath = 'C:/Users/hp/Downloads/aziiz.pdf';
         file_put_contents($pdfFilePath, $dompdf->output());
         $response = new Response(file_get_contents($pdfFilePath));
         $response->headers->set('Content-Type', 'application/pdf');
         $response->headers->set('Content-Disposition', 'attachment; filename="document.pdf"');
         return $response;
            }
 
}
