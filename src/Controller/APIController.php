<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class APIController extends AbstractController
{
    #[Route('/api/login_check', name:'api/login_check')]
    public function login(){
        $user = $this->getUser();
        return $this->json([
            'username' => $user->getUsername()
        ]);
    }

    
    #[Route('/api/products', name: 'a_p_i')]
    public function index(ProduitRepository $produitRepository): Response
    {    
        $products = $produitRepository->findAll();
        
        //ProduitRepository $produitRepository, NormalizerInterface $normalizer, SerializerInterface $seralizer

        // $normalizerProducts = $normalizer->normalize($products,null,['groups' => 'product:read']);

        // $json = json_encode($normalizerProducts);

       // $json = $seralizer->serialize($products,'json',['groups' => 'product:read']);
        
        $response  = $this->json($products, 200, [], ['groups' => 'product:read']);

        return $response;
        //return new JsonResponse($json, 200, [], true);
    }

    #[Route('/api/categories', name: 'all_categories')]
    public function getAllCategories(CategoryRepository $categoryRepository){
        $categories = $categoryRepository->findAll();
          

        $response = $this->json($categories, 200, [], ['groups' => 'product:read']);
        return $response;
    }

    #[Route('/api/products/category/{id}', name:"product_by_catId")]
    public function productsByCategoryId($id, ProduitRepository $produitRepository){
        $products = $produitRepository->findByCategory($id);

        return $this->json($products, 200, [], ['groups' => 'product:read']);
    }

    #[Route('/api/add', name:'add product', methods:["POST"])]
    public function addProduct(Request $request, SerializerInterface $seralizer, NormalizerInterface $normalizer, ValidatorInterface $validator){

        $json = $request->getContent();

        try{
        $product = $seralizer->deserialize($json,Produit::class,'json');

        $errors = $validator->validate($product);
        if(count($errors) > 0 ){
            return $this->json($errors,400);
        }

        $manager->persist($product);
        $manager->flush();

        return $this->json($product,201, [], ['groups' => 'product:read']);
        } catch(NotEncodableValueException $e){
            return $this->json([
                "statue" => 400,
                "message" =>$e->getMessage()
            ],400);
        }
    }

         
    #[Route('api/product/delete/{id}',name:'manage.product.delete')]
    public function deleteProduct(Produit $produit, ProductImageRepository $imagerepository){
        
        $conn = $this->getDoctrine()->getManager()->getConnection();

        $sql = "DELETE FROM  product_Image  WHERE product_id = :id";

        $stm = $conn->prepare($sql);

        $stm->execute(['id' =>  $produit->getId()]);

        $this->manager->remove($produit); 
        $this->manager->flush(); 

        return $this->redirectToRoute('manage');
    }
}
