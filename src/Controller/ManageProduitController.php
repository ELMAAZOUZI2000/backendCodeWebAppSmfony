<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\ProductImage;
use App\Form\ProduitFormType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductImageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ManageProduitController extends AbstractController
{
    public function __construct(EntityManagerInterface $manager, ProduitRepository $repository){
        $this->manager = $manager;
        $this->repository = $repository;
    }

    #[Route('/manage', name: 'manage')]
    public function index(): Response
    {
        $products = $this->repository->findAll();
        return $this->render('manage/index.html.twig', [
             'products' => $products,
        ]);
    }


      
    #[Route('/manage/new', name:'manage.product.new')]
    #[Route('/manage/edit/{id}', name:'manage.product.edit')]
    public function create(Produit $produit = null, Request $request): Response{   
        if($produit == null ){
             $produit= new Produit();
        }
        dump($request);
        $form = $this->createForm(ProduitFormType::class, $produit);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $images = $form->get('productImages')->getData();
            foreach($images as $image){
                $fichier = md5(uniqid()).'.'.$image->guessExtension();
                dump($fichier);
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                $img = new ProductImage();
                $img->setImageName($fichier);
                $img->setImageSize('75421'); 
                $produit->addProductImage($img);
            }

            $this->manager->persist($produit);
            dump($produit);
            $this->manager->flush(); 
            return $this->redirectToRoute('manage',['id'=> $produit->getId()]);
            
        }
        
        
        return $this->render('produit/create.html.twig',[
            'formProduit' =>$form->createView(),
            'editMode' => $produit->getId() != null,
            'produit' => $produit
        ]);
    }

     
    #[Route('manage/delete/{id}',name:'manage.product.delete')]
    public function deleteProduitById(Produit $produit, ProductImageRepository $imagerepository){
        
        $conn = $this->getDoctrine()->getManager()->getConnection();

        $sql = "DELETE FROM  product_Image  WHERE product_id = :id";

        $stm = $conn->prepare($sql);

        $stm->execute(['id' =>  $produit->getId()]);

        $this->manager->remove($produit); 
        $this->manager->flush(); 

        return $this->redirectToRoute('manage');
    }
    

    #[Route("delete/image/{id}" ,name: 'product.delete_image' )]
    public function deleteImage(ProductImage $image): Response{ 
        $nom = $image->getImageName();
        unlink($this->getParameter('images_directory').'/'.$nom);
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($image);
        $em->flush(); 

       return $this->redirectToRoute('manage.product.edit', ['id' => $image->getProduct()->getId()]);

    }
     
}
