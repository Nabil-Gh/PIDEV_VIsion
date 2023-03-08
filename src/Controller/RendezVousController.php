<?php

namespace App\Controller;
use App\Form\UpdateType;
use App\Form\RendezVousType;
use Twilio\Rest\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function afficherback(RendezVousRepository $repo,UserRepository $rp)
    {
        $khlifa=$repo->findAll();
        $med=$rp->find(23);
        $rv=$repo->findByuser($med);
        foreach ($rv as $event)
        if($event->isIsConfirmed() == true ){ 
        {
            $rdvs[]=[
                'start'=>$event->getDateRv()->format("Y-m-d H:i:s"),
                'end'=>$event->getDateRv()->modify("+2 hours")->format("Y-m-d H:i:s"),
                'title'=>$event->getPatient()->getPrenom().' '.$event->getPatient()->getNom(),
                'backgroundColor'=> $event->getBackgroundColor(),
                'borderColor'=> $event->getBackgroundColor(),
                'textColor' => 'black'
            ];
        }
    }
        $data = json_encode($rdvs);
        return $this->render('rendez_vous/afficherrv.html.twig', [
            'rdv' => $rv,
            'med'=>$med,
            'data'=>$data,
            'khlifa'=>$khlifa
        ]);
    }

    #[Route('/rendez_vous/addfront', name: 'add_rv')]
    public function addfront(ManagerRegistry $doctrine,Request $request,UserRepository $rp,RendezVousRepository $repo): Response
    {
        $message="";
        $user=$rp->find(32);
        $med=$rp->find(23);
        $em = $doctrine->getManager();
        $rendezvouss=$repo->findByuser($med);
        foreach ($rendezvouss as $event)
        if($event->isIsConfirmed() == true ){ 
        {
            $rdvs[]=[
                'title'=>'Occupé !',
                'start'=>$event->getDateRv()->format("Y-m-d H:i:s"),
                'end'=>$event->getDateRv()->modify("+2 hours")->format("Y-m-d H:i:s"),
                'backgroundColor'=> 'teal',
                'borderColor'=> 'green',
                'textColor' => 'black'
            ];
        }
    }
        $data = json_encode($rdvs);
        $rendezvous = new RendezVous();
        $form = $this->createForm(RendezVousType::class, $rendezvous);
        $form->handleRequest($request);
        $s=0;
        if($form->isSubmitted() && $form->isValid() ) {
            foreach($rendezvouss as $rv)
            {
                if($rendezvous->getDateRv()->format("Y-m-d H:i:s") >= $rv->getDateRv()->format("Y-m-d H:i:s") && $rendezvous->getDateRv()->format("Y-m-d H:i:s") <= $rv->getDateRv()->modify("+2 hours")->format("Y-m-d H:i:s")){
                    $s=1;
                    return $this->renderForm('rendez_vous/appointmentform.html.twig', [
                        'form' => $form,'s'=>$s,'data'=>$data
                    ]);
                }
                
            }
            $rendezvous->setIsConfirmed(false);
            $rendezvous-> setPatient($user);
            $rendezvous-> setMed($med);
            $em->persist($rendezvous);
            $em->flush();
           // $message="Votre rendez-vous avec votre specialiste est confirmé ! vous receverez un SMS lors de la confirmation de la part du Medecin.Nous espérons que vous vous rétablirez bientôt!";
            
            return $this->redirectToRoute('afficherfr');
        }
        return $this->renderForm('rendez_vous/appointmentform.html.twig', [
            'form' => $form,'s'=>$s,'data'=>$data,'message'=>$message
        ]);
    }


    #[Route('/rendez_vous/update/{id}', name: 'update_rv')]
    public function update(ManagerRegistry $doctrine,Request $request,$id,RendezVousRepository $repo,UserRepository $rp): Response
    {
        $em = $doctrine->getManager();
        $rendezvous = $doctrine->getRepository(RendezVous::class)->find($id);
        $form = $this->createForm(UpdateType::class, $rendezvous);
        $rv=$repo->findAll();
        $form->handleRequest($request);
        $med=$rp->find(23);
        
        if($form->isSubmitted() && $form->isValid()) {
            $sid = 'ACd656184a7906751dc2fc7e53bcdd3544';
            $token = '37682b7ab82527c5f19f99708c24b430';
            $client = new Client($sid, $token);
            $message = $client->messages->create(
                "+216".$rendezvous->getPatient()->getTelephone(), 
                [
                    'from' => '+16076899929', 
                    'body' => "Votre rendez-vous avec le médecin " . $rendezvous->getMed()->getNom() . " " . $rendezvous->getMed()->getPrenom() . " a été reporté pour le " . $rendezvous->getDateRv()->format("Y-m-d à H:i") . ". Merci pour votre compréhension !"
                ]
            );
          
            $em->flush();
            return $this->redirectToRoute('afficherrv');
        }
        return $this->renderForm('rendez_vous/update.html.twig', [
            'form' => $form,
            'khlifa'=>$rv,
            'med'=>$med
        ]);
    }

    #[Route('rendez_vous/remove/{id}', name: 'removerv')]
    public function removeback(ManagerRegistry $doctrine,$id): Response
    {
        $em= $doctrine->getManager();
        $rendezvous= $doctrine->getRepository(RendezVous::class)->find($id);
        $sid = 'ACd656184a7906751dc2fc7e53bcdd3544';
        $token = '37682b7ab82527c5f19f99708c24b430';
        $client = new Client($sid, $token);
        $message = $client->messages->create(
            "+216".$rendezvous->getPatient()->getTelephone(), 
            [
                'from' => '+16076899929', 
                'body' => "Votre rendez-vous avec le Medecin " . $rendezvous->getMed()->getPrenom() . " " . $rendezvous->getMed()->getNom() . " Prévu le :  " . $rendezvous->getDateRv()->format("Y-m-d à H:i") . "a été annulé. "
            ]
        );
        $em->remove($rendezvous);
        $em->flush();
        return $this->redirectToRoute('afficherrv');
    }


    #[Route('rendez_vous/afficherfront', name: 'afficherfr')]
    public function afficherfront(RendezVousRepository $repo,UserRepository $rp): Response
    {
        $user=$rp->find(23);
        $rendezvous = $repo->findbyuser($user);
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
        if($form->isSubmitted() && $form->isValid()) {
            $rendezvous->setIsConfirmed(false);
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
        $sid = 'ACd656184a7906751dc2fc7e53bcdd3544';
        $token = '37682b7ab82527c5f19f99708c24b430';
        $client = new Client($sid, $token);
        $message = $client->messages->create(
            "+216".$rendezvous->getMed()->getTelephone(), 
            [
                'from' => '+16076899929', 
                'body' => "Votre rendez-vous avec le Patient " . $rendezvous->getPatient()->getPrenom() . " " . $rendezvous->getPatient()->getNom() . " Prévu le :  " . $rendezvous->getDateRv()->format("Y-m-d à H:i") . "a été annulé. "
            ]
        );
        return $this->redirectToRoute('afficherfr');
    }

    #[Route('/searchuserajax', name:'ajaxuser')]
     
    public function searchajax(Request $request ,RendezVousRepository $repository,UserRepository $rp)
    {
        $repository = $this->getDoctrine()->getRepository(RendezVous::class);
        $requestString=$request->get('searchValue');
        $med=$rp->find(23);
        $Users = $repository->findByMedecinOrPatient($requestString,$med);
        
        return $this->render('rendez_vous/ajax.html.twig', [
            'rdv'=>$Users,
        ]);
    }

    #[Route('/rendez_vous/c/{id}', name: 'confirmerrvm')]
    public function confirmerrv(ManagerRegistry $doctrine,$id,RendezVousRepository $repo): Response
    {
        $em= $doctrine->getManager();
        $user=$repo->find(23);
        $rendezvous=$repo->find($id);
        $rendezvous->setIsConfirmed(true);
        $em->flush();
        $sid = 'ACd656184a7906751dc2fc7e53bcdd3544';
        $token = '37682b7ab82527c5f19f99708c24b430';
        $client = new Client($sid, $token);
        $message = $client->messages->create(
            "+216".$rendezvous->getPatient()->getTelephone(), 
            [
                'from' => '+16076899929', 
                'body' => "Votre rendez-vous avec le Medecin " . $rendezvous->getMed()->getPrenom() . " " . $rendezvous->getMed()->getNom() . " Prévu le :  " . $rendezvous->getDateRv()->format("Y-m-d à H:i") . "a été confirmé . Vous pouvez le consulter sur notre application maintenant !  "
            ]
        );
        return $this->redirectToRoute('afficherrv');

    }

    #[Route('/rendez_vous/historique', name: 'historique')]
    public function historique(ManagerRegistry $doctrine,Request $request,RendezVousRepository $repo,UserRepository $rp): Response
    {
        $data=null;
        $med=$rp->find(23);
        $expire=$repo->findByexpire($med);
        $rv=$repo->findAll();
        
        return $this->render('rendez_vous/afficherrv.html.twig', [
            'rdv'=>$expire,'med'=>$med,'khlifa'=>$rv,'data'=>$data
        ]);

    }

   


}

    




