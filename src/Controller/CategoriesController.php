<?php

namespace App\Controller;
use App\Entity\Categories;
use App\Form\CategoriesFormType;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    #[Route('/categories', name: 'app_categories')]
    public function index(): Response
    {
        return $this->render('categories/index.html.twig', [
            'controller_name' => 'CategoriesController',
        ]);
    }

    #[Route('/ajout_categories', name: 'ajout_categories')]

    function Ajout(Request $request)
    {
        $categories=new Categories();
        $form=$this->createForm(CategoriesFormType::class,$categories);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($categories);
            $em->flush();
            return $this->redirectToRoute('afficher_categories');

        }
        return $this->render('categories/ajout_categories.html.twig',[
            'f'=>$form->createView(),

        ]);
    }
    #[Route('/afficher_categories', name: 'afficher_categories')]

    function Affiche(CategoriesRepository $repository){
        $categories= $repository->findAll();
        return $this->render('categories/afficher_categories.html.twig',['categories'=>$categories]);
    }
    #[Route('/update_categories/{{id}}', name: 'update_categories')]
 
    function Update(CategoriesRepository $repository,$id,Request $request)
    {
        $categories = $repository->find($id);
        $form = $this->createForm(CategoriesFormType::class, $categories);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("afficher_categories");
        }
        return $this->render('categories/Update_categories.html.twig',
            [
                'f' => $form->createView(),
                "form_title" => "Modifier une catégorie"
            ]);
    }
 /**
     * @param $id
     * @param CategorieRepository $rep
     * @route ("/delete_categories/{id}", name="delete_categories")
     */
    function Delete($id,CategoriesRepository $rep){
        $categorie=$rep->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();
        return $this->redirectToRoute('afficher_categories');
    }


}
