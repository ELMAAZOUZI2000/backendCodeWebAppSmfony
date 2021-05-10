<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\SearchProduit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SearchProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('minPrice',MoneyType::class,[
                'label' => false,
                'required' => false,
                'attr'=>[
                    'placeholder'=> 'Min Price'
                ]
            ])
            ->add('maxPrice',MoneyType::class,[
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder'=> 'Max Price'
                ]
                
            ])
            ->add('categories',EntityType::class, [
                'class' => Category::class,
                'required' => false,
                'choice_label' => 'title',
                'multiple' => true,
                'label' =>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchProduit::class,
        ]);
    }
}
