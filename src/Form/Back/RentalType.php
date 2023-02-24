<?php

namespace App\Form\Back;

use App\Entity\Rental;
use App\Entity\RentalOption;
use App\Entity\Transport;
use App\Entity\User;
use App\Enums\RentalTypes;
use App\FakeApi\CelestialObjects;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

class RentalType extends AbstractType
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rent_type', EnumType::class, [
                'class' => RentalTypes::class,
                'choice_label' => fn ($choice) => RentalTypes::getValuesTranslated($this->translator)[$choice->value],
            ])
            ->add('description')
            ->add('price')
            ->add('max_capacity')
            ->add('room_count')
            ->add('bathroom_count')
            ->add('longitude')
            ->add('latitude')
            ->add('uuid')
            ->add('owner', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
            ])
            ->add('options', EntityType::class, [
                'class' => RentalOption::class,
                'choice_label' => function (?RentalOption $rentalOption) {
                    return $rentalOption ? $this->translator->trans($rentalOption->getName()) : '';
                },
                'multiple' => true,
                'expanded' => true,
                'label' => 'rental_options',
                'required' => false,
            ])
            ->add('celestial_object', ChoiceType::class, [
                'choices' => array_flip(CelestialObjects::getValuesOrderedByTranslation($this->translator)),
                'label' => 'celestial_object',
            ])
            ->add('transports', EntityType::class, [
                'class' => Transport::class,
                'choice_label' => function (?Transport $transport) {
                    return $transport ? $this->translator->trans($transport->getName()) : '';
                },
                'multiple' => true,
                'expanded' => true,
                'label' => 'transports',
                'required' => false,
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'download_uri' => true,
                'image_uri' => true,
                'asset_helper' => true,
                'label' => 'main_image',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rental::class,
        ]);
    }
}
