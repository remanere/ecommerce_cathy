<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'label' => 'Nom du produit',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ecrire ici le nom...'
                ]
            ])
            ->add('price',MoneyType::class,[
                'label' => 'Prix du produit',
                'required' => false,
                'divisor' => 100
            ])
            ->add('imagePath',TextType::class,[
                'data_class' => null,
                'label' => 'Image du produit',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ajouter le chemin de l\'image ici'
                ]

            ])

            ->add('image',FileType::class,[
                'data_class' => null,
                'label' => 'Ajouter une image',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '3m',
                        'maxSizeMessage' => 'Le fichier ne doit pas dépasser 3 Mo.',
                    ]),
                ]

            ])
            
            ->add('category',EntityType::class,[
                'label' => 'Choisir la catégorie',
                'placeholder' => '-- Choisir --',
                'required' => false,
                'class' => Category::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
