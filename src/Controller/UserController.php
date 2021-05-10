<?php

namespace App\Controller;

use App\Entity\User; 
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
 
class UserController extends AbstractController
{   

    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager; 
    }
 


    #[Route('/user/inscription', name: 'user.inscription')]
    public function index(UserRepository $repository, Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder): Response
    {   
        $user = new User();
        $form = $this->createForm(UserFormType::class,$user);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush(); 

            return $this->redirectToRoute('login');
        }

        return $this->render('user/index.html.twig', [  
            'form'=> $form->createView(), 
        ]);
    }

    #[Route('/user/doInscription', name:'doInscription')]
    public function doInscription(Request $request, ValidatorInterface $validator, \Swift_Mailer $mailer) {
        $data = $request->getContent();

        $user = $this->get('serializer')->deserialize($data,'App\Entity\User','json');
        $user->confirmPassword = $user->getPassword();

        $errors = $validator->validate($user);
        
        if(count($errors) > 0){
            return $this->json($errors,400,[]);
        }
         
        $message = (new \Swift_Message('Activation de votre compte'))
                    ->setFrom('SymfonyTest@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody( $this -> render('user/activation.html.twig', ['userId' => $user->getId()]),'text/html');
        
        $mailer->send($message);
        dump('DONE/SENT SUCCESS');
        $this->manager->persist($user);
        $this->manager->flush();  
        
        $response = array(
            'code'    => 1,
            'message' => 'user added successfully to database',
            'errors'  => null,
            'result'  => null
        );
        
        return new JsonResponse($response, Response::HTTP_OK);
    }

    #[Route('user/{id}/email/validate/', name:'validation')]
    public function activation(User $user){
        if($user){
            $user->setAccountValidation(true);
        }
    }
  
}   
