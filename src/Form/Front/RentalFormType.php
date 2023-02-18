<?php

namespace App\Form\Front;

use App\Entity\Rental;
use App\Entity\RentalOption;
use App\Entity\Transport;
use App\Enums\RentalTypes;
use App\FakeApi\CelestialObjects;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

class RentalFormType extends AbstractType
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rent_type', ChoiceType::class, [
                'choices' => RentalTypes::getValues(),
                'label' => 'rent_type',
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'describe_your_rental',
                ],
                'label' => 'description',
            ])
            ->add('max_capacity', IntegerType::class, [
                'attr' => [
                    'placeholder' => 'max_guests_available',
                ],
                'label' => 'capacity',
            ])
            ->add('price', IntegerType::class, [
                'attr' => [
                    'placeholder' => 'price_by_night',
                ],
                'label' => 'price',
            ])
            ->add('room_count', IntegerType::class, [
                'attr' => [
                    'placeholder' => 'room_count',
                ],
                'label' => 'rooms',
            ])
            ->add('bathroom_count', IntegerType::class, [
                'attr' => [
                    'placeholder' => 'bathroom_count',
                ],
                'label' => 'bathrooms',
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
            ->add('longitude', IntegerType::class, [
                'label' => 'longitude',
            ])
            ->add('latitude', IntegerType::class, [
                'label' => 'latitude',
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
                'required' => true,
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
