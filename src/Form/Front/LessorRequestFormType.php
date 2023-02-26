<?php

namespace App\Form\Front;

use App\Entity\User;
use App\Entity\UserLessorRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
            ->add('lessor', LessorProfileFormType::class, [
                'data_class' => User::class,
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
