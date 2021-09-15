<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\Zone;
use App\Repository\CategoryRepository;
use App\Repository\ZoneRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('category',EntityType::class, [
                'class' => Category::class,
                'query_builder' => function (CategoryRepository $repository) {
                    return $repository->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
            ->add('zone',EntityType::class, [
                'class' => Zone::class,
                'query_builder' => function (ZoneRepository $repository) {
                    return $repository->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'choice_label' => 'name',
            ])

            ->add('email',EmailType::class, [
                'label' => 'Email'
            ])
            ->add('logoName',FileType::class,[
                'label' => 'Photo De Profil',
                'data_class' => null,
                'required' => false,
                "mapped" => false
            ])
            ->add('mobile_phone', NumberType::class, [
                'label' => 'Numéro de telephone'
            ])
            ->add('username', TextType::class, [
                'label' => 'Nom'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
