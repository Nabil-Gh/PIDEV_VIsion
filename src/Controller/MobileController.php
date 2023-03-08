<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\RendezVousRepository;
use App\Repository\UserRepository;
use App\Entity\RendezVous;
use DateTime;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;

class MobileController extends AbstractController
{
    #[Route('/AllrdvsJson', name: 'listerdv', methods: ['GET'])]
    public function allrdvsJson( RendezVousRepository $repo, NormalizerInterface $serialiser)
    {
        $user = $repo->findAll();
        
          return $this->json([
                'data'=>$user
          ],200,[],[ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function()
          {return 'symfony4';}]
          );            
    }
    

    #[Route("/addrdvssJson", name: "addrdvsJson")]
public function addrdvsJson(Request $req, NormalizerInterface $Normalizer, UserRepository $rp)
{
    $med = $rp->find(23);
    $patient = $rp->find(32);
    $rv = new RendezVous();
    $type_rv = $req->query->get("type_rv");
    $em = $this->getDoctrine()->getManager();
    $rv->setDateRv(new \DateTimeImmutable("now"));
    $rv->setTypeRv($type_rv);
    $rv->setMed($med);
    $rv->setPatient($patient);
    $rv->setIsConfirmed(false);
    $em->persist($rv);
    $em->flush();

    //$jsonContent = $Normalizer->normalize($Forum, 'json', ['groups' => "forums"]);
    //return new Response(json_encode($jsonContent));
    return $this->json($rv, 200, [], ['groups' => 'rdvs']);
}

    
     

    #[Route("/updaterdvsJson", name: "updaterdvsJson")]
     public function updaterdvJson(Request $req){

    $em = $this->getDoctrine()->getManager();
    $rv = $this->getDoctrine()->getManager()
        ->getRepository(RendezVous::class)
        ->find($req->get("id"));

    
    $rv->setTypeRv($req->get("type_rv"));
    $em->persist($rv);
    $em->flush();
    $serializer = new Serializer([new ObjectNormalizer()]);
    $formatted = $serializer->normalize("Rendez vous a ete modifiee avec success.");
    return new JsonResponse($formatted);
    

    }



     

    
    #[Route("/deleterdvsJson", name: "deleterdvsJson")]
     public function deleterdvJson(Request $request) {

        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $RendezVous = $em->getRepository(RendezVous::class)->find($id);
        if($RendezVous!=null ) {
            $em->remove($RendezVous);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("Rendez vous a ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id Rendez vous vous invalide.");


    }
}
