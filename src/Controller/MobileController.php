<?php


namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use App\Serializer\CircularReferenceHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class MobileController extends  AbstractController
{


     /**
      * @Route("/ajout_user", name="ahout_user")
      * @Method("POST")
      */

     public function ajouterUserAction(Request $request, UserPasswordHasherInterface $userPasswordHasher)
     {
         $user = new User();
         $nom = $request->query->get("nom");
         $prenom = $request->query->get("prenom");
         $email = $request->query->get("email");
         $password = $request->query->get("password");
         $telephone = $request->query->get("telephone");
         $adress = $request->query->get("adress");
         $sexe = $request->query->get("sexe");

         $em = $this->getDoctrine()->getManager();
         
         
         $user->setNom($nom);
         $user->setPrenom($prenom);
         $user->setEmail($email);
         $user->setTelephone($telephone);
         $user->setAdress($adress);
         $user->setDatecr(new \DateTime("now"));
         $user->setSexe($sexe);
         $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $password
            )
        );

         


         $em->persist($user);
         $em->flush();
         $serializer = new Serializer([new ObjectNormalizer()]);
         $formatted = $serializer->normalize($user);
         return new JsonResponse($formatted);

     }

    

      /******************Supprimer Article*****************************************/

     /**
      * @Route("/supprimer_user_mobile", name="supp_user_mobile")
      * @Method("DELETE")
      */

      public function deleteUserAction(Request $request) {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);
        if($user!=null ) {
            $em->remove($user);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("Le compte a ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id User invalide.");


    }

     /******************Modifier Article*****************************************/
    /**
     * @Route("/update_user_mobile", name="update_user_mobile")
     * @Method("POST")
     */
    
     public function modifierUserAction(UserRepository $UserRepository,Request $request,UserPasswordHasherInterface $userPasswordHasher) {
      
         $em = $this->getDoctrine()->getManager();
 
         $user = $UserRepository->find($request->get('id'));
        
        
         $user->setTelephone($request->get('telephone'));
         $user->setAdress($request->get('adress'));
         $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $password = $request->get("password")

            )
        );
 
         
         $em->persist($user);
         $em->flush();
 
         return new JsonResponse($user);
 
     }

     
     /**
      * @Route("/afficher_user_mobile", name="afficher_user_mobile")
      */
      public function affUserAction(UserRepository $repository):JsonResponse
      {
 
         
          $user = $repository->findAll();
        
          return $this->json([
                'data'=>$user
          ],200,[],[ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function()
          {return 'symfony4';}]
          );
          
          
           
      }
 
 

 }