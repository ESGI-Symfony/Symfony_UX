<?php

namespace App\Form\Back;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('nickname')
            ->add('password')
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'multiple' => true,
                'expanded' => true,
                'choices'  => [
                    'User' => 'ROLE_USER',
                    'Lessor' => 'ROLE_LESSOR',
                    'Admin' => 'ROLE_ADMIN',
                ],
            ])
            ->add('isVerified')
        ;

        // transform roles array to string
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // no need to transform array as it is a choice type
                    return $rolesArray;
                },
                function ($rolesString) {
                    // remake keys of choice array
                    return array_values($rolesString);
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

