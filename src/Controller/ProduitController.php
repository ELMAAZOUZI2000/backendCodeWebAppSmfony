<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Category;
use App\Entity\SearchProduit;
use App\Form\ProduitFormType;
use App\Form\SearchProduitType;
use App\Repository\ProduitRepository; 
use App\Repository\CategoryRepository; 
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; 
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  


#[Route('/stock')]
class ProduitController extends AbstractController
{   

    public function __construct(EntityManagerInterface $manager, ProduitRepository $repository ){
        $this->manager = $manager;
        $this->repository = $repository; 
    }

    #[Route('/', name: 'home')]
    public function accueil(){ 
        $Latestproducts = $this->repository->findAllLatestProducts();
        return $this->render('produit/accueil.html.twig',[  
            'products' => $Latestproducts,
        ]);
    }
    
    #[Route('/product', name: 'product.index')]
    public function product(Request $request){ 

        $search = new SearchProduit();
        $form = $this->createForm(SearchProduitType::class, $search);
        $form->handleRequest($request); 

        $Allproducts = $this->repository->findAllProducts($search);

        return $this->render('produit/index.html.twig',[  
            'products' => $Allproducts,
            'form' => $form->createView(),
        ]);
    }
    
    
    #[Route('/{id}', name:'product.show')]
    public function showProduit(Produit $produit): Response{ 
        return $this->render('produit/show.html.twig',[
            'product' => $produit
        ]);
    } 
  

    #[Route('/categories', name: 'categories')]
    public function showCategories(CategoryRepository $categoryRepository){
        $categories = $categoryRepository->findAll();
        return $this->render('category/showC.html.twig',[ 
            'categories'=>$categories
        ]);
    }
    
    
  
    #[Route('/category/{id}', name:'show_category_info')]
    public function showCategoryInfo(Category $category,CategoryRepository $categoryRepository){  
        return $this->render('category/showInfo.html.twig',[
            'category' => $category
        ]);
    }
    
    #[Route('/cat/{id}', name:'delete_category')]
    public function deleteCategory(Category $category,CategoryRepository $categoryRepository, EntityManagerInterface $manager){ 
       
        $this->deleteProduitByCategoryId($category, $manager);
        
        $manager->remove($category); 

        $manager -> flush();
        return $this->redirectToRoute('categories');
    }

    // #[Route('/cat/{id}', name:'delete_category')]
    // public function deleteCategoryById(Category $category, EntityManagerInterface $manager){  
    //     dump($category);
    //     $manager -> remove($category);
    //     $manager -> flush();

    //     return $this->redirectToRoute('category');
    // } 
    
    
    #[Route('/delete/category/{id}', name:'delete_produit_by_catId')]
    public function deleteProduitByCategoryId(Category $category ,EntityManagerInterface $manager){
        
        $res =  $this->getDoctrine()
                    ->getRepository(Produit::class) 
                    ->deleteByCategoryId($category->getId());

        $manager->flush(); 
        return $this->redirectToRoute('categories');
    }
    
    #[Route('/product/{id}', name:'show_product')]
    public function showProduct($id){
        $product = $this->getDoctrine()->getRepository(Produit::class)->findOneById($id);
         
        if(empty($product)){
            $response = array (
                'code' => -1,
                'message' => 'produict not found',
                'error' => null,
                'result' => null
            );
        
            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }
         
         
        return $this->json($product,200,[],['groups' => ['product:read']]);
    }

    #[Route("/api/create/product", name:"create_product")]
    public function createProduct(Request $request){
        $data = $request->getContent();

        $product = $this->get('serializer')->deserialize($data, 'App\Entity\Produit', 'json');

        // $reponse = $validate->validateRequest($product);

        // if(!empty($reponse)){
        //     return new JsonResponse($reponse, Response::HTTP_BAD_REQUEST);
        // }
            
        $cat = $this->getDoctrine()->getRepository(Category::class)->findOneByTitle($product->getCategory()->getTitle());
        $product->setCategory($cat);
        $this->manager->persist($product);
        $this->manager->flush();

        $response = array(
            'code' =>1,
            'message' =>'Product created',
            'errors' => null,
            'result' =>null
        );

        return new JsonResponse($response, Response::HTTP_CREATED);
    } 
    
}
