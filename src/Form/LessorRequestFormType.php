<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\UserLessorRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LessorRequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('motivation', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'your_motivation_explained',
                ],
                'required' => true,
            ])
            ->add('firstname', TextType::class, [
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'your_name',
                ],
                'required' => true,
            ])
            ->add('lastname', TextType::class, [
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'your_last_name',
                ],
                'required' => true,
            ])
            ->add('phone', TextType::class, [
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'your_galactic_phone',
                ],
                'required' => true,
            ])
            ->add('lessor_number', NumberType::class, [
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'your_lessor_number',
                ],
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserLessorRequest::class,
        ]);
    }
}
