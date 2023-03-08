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
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\QrCode;
use App\Services\QrcodeService;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use App\Repository\ReviewRepository;
use App\Form\ReviewType;
use App\Entity\Review;


use Endroid\QrCode\Writer\PngWriter;

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

    function Ajout(QrcodeService $qrcodeService,Request $request,SluggerInterface $slugger)
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
            $qrCode = $qrcodeService->qrcode($Produits->getLibelle(),$Produits->getDescription(),$Produits->getPrix());
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
       
        $categories=$repo->findAll();
       // $rates=$repository->get_prod_with_moy();
    
        $produits=$repository->findAll();
        $panier = $session->get("panier", []);

        // On "fabrique" les données
        $dataPanier = [];
        $total = 0;
        $panier=[];
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
    function Affiche_frontdetails(ProduitsRepository $repository,CategoriesRepository $repo,$id ,ReviewRepository $rep,Request $requeste){
        $review=new Review();
        $review->setProduitId($id);
        $form=$this->createForm(ReviewType::class,$review);
        $form->handleRequest($requeste);
        $comments=$repository->show_comment($id);
        //$produits= $repository->get_prod_with_moy();


        if($form->isSubmitted() && $form->isValid())
        {

            $em=$this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();
            return $this->redirectToRoute('afficher_produits_front');


        }
        $produits= $repository->find($id);
        $img="produit".$produits->getLibelle().".png";

        

        return $this->render('produits details.html.twig',['c'=>$produits,'img'=>$img,'comments'=>$comments,'f'=>$form->createView()]);
    }

    #[Route('/afficher_produits_tri', name: 'afficher_produits_tri')]

    function AfficheTri(ProduitsRepository $repository){
        $produits= $repository->findAll();
        $pr= $repository->findByPrix($produits);
        return $this->render('produits/afficher_produits.html.twig',['produits'=>$pr]);
    }
    #[Route('/searchproduitajax', name:'ajaxproduit')]
    public function searchajax(Request $request ,ProduitsRepository $repository)
    {
        $repository = $this->getDoctrine()->getRepository(Produits::class);
        $requestString=$request->get('searchValue');
        $produits = $repository->findByLibelle($requestString);
        
        
        return $this->render('produits/ajax.html.twig', [
            'produits'=>$produits,
        ]);
    }
    /**
     * @Route("/produits/likes/{id}", name="produits_likes")
     */
    public function likes(ProduitsRepository $rp,$id): Response
    {
        $produits=$rp->find($id);
        $produits->setLikes($produits->getLikes() + 1);
        $entityManager = $this->getDoctrine()->getManager();
       
        $entityManager->flush();
        
        return $this->redirectToRoute('afficher_produits_front', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/produits/dislikes/{id}", name="produits_dislikes")
     */
    public function dislikes(ProduitsRepository $rp,$id): Response
    {
        $produits=$rp->find($id);
        $produits->setDislikes($produits->getDislikes() + 1);
        $entityManager = $this->getDoctrine()->getManager();
      
        $entityManager->flush();
        
        return $this->redirectToRoute('afficher_produits_front', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/filtreprix', name:'filtreprix', methods : ['POST','GET'])]
    public function filtreprix(Request $request ,ProduitsRepository $repository,CategoriesRepository $repo, SessionInterface $session)
    {
        $prix=$request->get("from");
        $prixx=$request->get("to");
       
        $produit=$repository->findByprixintervalle($prix,$prixx);
        $categories=$repo->findAll();

        $panier = $session->get("panier", []);

        // On "fabrique" les données
        $dataPanier = [];
        $total = 0;
        $panier=[];
        foreach($panier as $id => $quantite){
            $Produits = $repository->find($id);
            $dataPanier[] = [
                "produit" => $Produits,
                "quantite" => $quantite
            ];
            $total += $Produits->getPrix() * $quantite;
            
        
        
    }
    
        return $this->render('produits/afficher_produits_front.html.twig', [
        'produits'=>$produit,'categories'=>$categories,"dataPanier"=>$dataPanier, "total"=>$total
    ]);
   

}



}