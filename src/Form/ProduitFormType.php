<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Category;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType; 
 
class ProduitFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class , [
                'attr' => [
                    'placeholder'=> 'Titre de l\'article'
                    ],
                    'required' => true
            ])
            ->add('reference',null, [
                'attr' => [
                    'placeholder'=> 'Reference de l\'article'
                ],
                'required' => true
            ])
            ->add('category',EntityType::class,[
                'class' => Category::class,
                'choice_label' => 'title'
            ])
            ->add('description', null,[
                'attr' => [
                    'placeholder'=> 'Description de l\'article'
                    ],
                'required' => true
            ])
            ->add('prix',MoneyType::class, [
                'attr' => [
                    'placeholder'=> 'Prix de l\'article'
                    ],
                'required' => true
            ])
            ->add('productImages', FileType::class,[
                'label' => false,
                'multiple'=>true,
                'required' => false,
                'mapped' => false,   
            ])
            // ->add('productImages', CollectionType::class, [  
            //      'entry_type'=> EmailType::class,
            //      'allow_add' => true,
            //      'allow_delete' => true,
            //      'prototype' => true
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
