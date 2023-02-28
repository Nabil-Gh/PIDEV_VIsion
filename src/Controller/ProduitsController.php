<?php

namespace App\Controller;
use App\Entity\Categories;
use App\Entity\Produits;

use App\Form\CategoriesFormType;
use App\Form\ProduitsFormType;

use App\Repository\ProduitsRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProduitsController extends AbstractController
{
    #[Route('/produits', name: 'app_produits')]
    public function index(): Response
    {
        return $this->render('produits/index.html.twig', [
            'controller_name' => 'ProduitsController',
        ]);
    }


    #[Route('/ajout_produits', name: 'ajout_produits')]

    function Ajout(Request $request,SluggerInterface $slugger)
    {
        $Produits=new Produits();
        $form=$this->createForm(ProduitsFormType::class,$Produits);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $brochureFile = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('produit_image'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $Produits->setImage($newFilename); 
            }

            $em=$this->getDoctrine()->getManager();
            $em->persist($Produits);
            $em->flush();
            return $this->redirectToRoute('afficher_produits');

        }
        return $this->render('produits/ajout_Produits.html.twig',[
            'f'=>$form->createView(),

        ]);
    }

    #[Route('/afficher_produits', name: 'afficher_produits')]

    function Affiche(ProduitsRepository $repository){
        $produits= $repository->findAll();
        return $this->render('produits/afficher_produits.html.twig',['produits'=>$produits]);
    }
    #[Route('/update_produits/{{id}}', name: 'update_produits')]
 
    function Update(ProduitsRepository $repository,$id,Request $request)
    {
        $produits = $repository->find($id);
        $form = $this->createForm(ProduitsFormType::class, $produits);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("afficher_produits");
        }
        return $this->render('produits/update_produits.html.twig',
            [
                'f' => $form->createView(),
                "form_title" => "Modifier un produit"
            ]);
    }

     /**
     * @param $id
     * @param ProduitsRepository $rep
     * @route ("/delete_produits/{id}", name="delete_produits")
     */
    function Delete($id,ProduitsRepository $rep){
        $produits=$rep->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($produits);
        $em->flush();
        return $this->redirectToRoute('afficher_produits');
    }


    #[Route('/afficher_produits_front', name: 'afficher_produits_front')]

    function Affiche_front(ProduitsRepository $repository,CategoriesRepository $repo, SessionInterface $session){
        $produits= $repository->findAll();
        $categories=$repo->findAll();

        $panier = $session->get("panier", []);

        // On "fabrique" les données
        $dataPanier = [];
        $total = 0;

        foreach($panier as $id => $quantite){
            $Produits = $repository->find($id);
            $dataPanier[] = [
                "produit" => $Produits,
                "quantite" => $quantite
            ];
            $total += $Produits->getPrix() * $quantite;
        }
        return $this->render('produits/afficher_produits_front.html.twig',compact('categories','produits',"dataPanier", "total"));
    }
    
    #[Route('/afficher_produits_front/{id}', name: 'afficher_produits_front_details')]

    function Affiche_frontdetails(ProduitsRepository $repository,CategoriesRepository $repo,$id){
        $produits= $repository->find($id);
       
        return $this->render('produits details.html.twig',['c'=>$produits,]);
    }

    #[Route('/afficher_produits_tri', name: 'afficher_produits_tri')]

    function AfficheTri(ProduitsRepository $repository){
        $produits= $repository->findAll();
        $pr= $repository->findByPrix($produits);
        return $this->render('produits/afficher_produits.html.twig',['produits'=>$pr]);
    }
   

}



