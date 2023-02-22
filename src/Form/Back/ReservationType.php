<?php

namespace App\Form\Back;

use App\Entity\Rental;
use App\Entity\Reservation;
use App\Entity\User;
use App\Enums\RentalTypes;
use App\FakeApi\CelestialObjects;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ReservationType extends AbstractType
{

    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_begin', DateType::class, [
                'widget' => 'single_text',
                'input'  => 'datetime'
            ])
            ->add('date_end', DateType::class, [
                'widget' => 'single_text',
                'input'  => 'datetime'
            ])
            ->add('payment_token')
            ->add('review_mark')
            ->add('review_comment')
            ->add('rental', EntityType::class, [
                'class' => Rental::class,
                'choice_label' => fn ($choice) => $choice->getId() . ' ' . RentalTypes::getValuesTranslated($this->translator)[$choice->getRentType()->value] . ' ' . CelestialObjects::getValuesTranslated($this->translator)[$choice->getCelestialObject()]
            ])
            ->add('buyer', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
