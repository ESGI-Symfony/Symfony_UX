<?php

namespace App\Form\Front;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isLessor = in_array('lessor', $options['validation_groups']);
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'your_email',
                ]
            ])
            ->add('nickname', TextType::class, [
                'attr' => [
                    'placeholder' => 'your_nickname',
                ],
            ])
            ->add('firstname', TextType::class, [
                'attr' => [
                    'placeholder' => 'your_name',
                ],
                'required' => $isLessor
            ])
            ->add('lastname', TextType::class, [
                'attr' => [
                    'placeholder' => 'your_last_name',
                ],
                'required' => $isLessor
            ])
            ->add('phone', TextType::class, [
                'attr' => [
                    'placeholder' => 'your_galactic_phone',
                ],
                'required' => $isLessor
            ])
        ;
        if ($isLessor) {
            $builder->add('lessor_number', IntegerType::class, [
                'attr' => [
                    'placeholder' => 'your_lessor_number',
                ],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'role' => 'ROLE_USER'
        ]);
    }
}
