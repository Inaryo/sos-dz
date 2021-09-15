<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\UserSearch;
use App\Entity\Zone;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use App\Repository\ZoneRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('zone',EntityType::class,[
                'class' => Zone::class,
                'label' => 'forms.product',
                'required' => false,
                'choice_label' => 'name',
                'query_builder' => function (ZoneRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                }
            ])
            ->add('category',EntityType::class,[
                'class' => Category::class,
                'label' => 'forms.product',
                'required' => false,
                'choice_label' => 'name',
                'query_builder' => function (CategoryRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserSearch::class,
            'translation_domain' => 'forms',
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }
    public function getBlockPrefix()
    {
        return '';
    }

}
