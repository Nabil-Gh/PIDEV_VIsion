<?php


namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;

 use App\Entity\Reclamation;
 use App\Serializer\CircularReferenceHandler;
 use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 use Symfony\Component\HttpFoundation\JsonResponse;
 use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
 use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;
use App\Repository\ReclamationRepository;


class recmobilecontroller extends  AbstractController
{


    /******************Ajouter Reclamation*****************************************/
     /**
      * @Route("/addArticle", name="add_Article")
      *  
      */

     public function ajouterArticleAction(Request $request)
     {
         $Reclamation = new Reclamation();
         $objet = $request->query->get("object");
       
         $type = $request->query->get("type");
         $description = $request->query->get("description");
      
         $em = $this->getDoctrine()->getManager();
         $Reclamation->setObjet($objet);
         $Reclamation->setDescription($description);
        
         $Reclamation->setType($type);
         

         $em->persist($Reclamation);
         $em->flush();
         $serializer = new Serializer([new ObjectNormalizer()]);
         $formatted = $serializer->normalize($Reclamation);
         //return new JsonResponse($formatted);
         dump($formatted);
         die;

     }

    

      /******************Supprimer Reclamation*****************************************/

     /**
      * @Route("/deleteArticle", name="delete_Article")
      * @Method("DELETE")
      */

      public function deleteArticleAction(Request $request) {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $Reclamation = $em->getRepository(Reclamation::class)->find($id);
        if($Reclamation!=null ) {
            $em->remove($Reclamation);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("Article a ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id Article invalide.");


    }

     /******************Modifier Reclamation
*****************************************/
    /**
     * @Route("/updateArticle", name="update_Article")
     * @Method("POST")
     */
    
     public function modifierArticleAction(ReclamationRepository $reclamationRepository,Request $request) {
      
 
         $em = $this->getDoctrine()->getManager();
 
         $reclamation = $reclamationRepository->find($request->get('id'));
        
         $reclamation->setEtat($request->get('etat'));
         $reclamation->setDescription($request->get('description'));
         $reclamation->setType($request->get('type'));
         $reclamation->setObjet($request->get('objet'));
     
 
         $em->persist($reclamation);
         $em->flush();
 
         return new JsonResponse($reclamation);
 
     }

     
     /**
      * @Route("/displayArticle", name="display_article")
      */
      public function allRecAction(ReclamationRepository $repository):JsonResponse
      {
 
         
          $reclamation = $repository->findAll();
       

          return $this->json([
                'data'=>$reclamation
          ],200,[],[ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function()
          {return 'symfony4';}]
          );
          
          
           
      }
 
 

    }