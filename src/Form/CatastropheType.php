<?php

namespace App\Form;

use App\Entity\Catastrophe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CatastropheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class)
            ->add('logo',FileType::class,[
                'label' => 'Image Représentatif',
                'data_class' => null,
                'required' => false,
                "mapped" => false
            ])
            ->add("description",TextType::class,[
                "label" => "Description"
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Catastrophe::class,
        ]);
    }
}
