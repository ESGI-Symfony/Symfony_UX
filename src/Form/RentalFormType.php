<?php

namespace App\Form;

use App\Entity\Rental;
use App\Entity\RentalOption;
use App\Entity\Transport;
use App\Entity\User;
use App\Entity\UserLessorRequest;
use App\Enums\RentalTypes;
use App\FakeApi\CelestialObjects;
use App\FakeApi\Systems;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

class RentalFormType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rent_type', ChoiceType::class, [
                'choices' => RentalTypes::getValues()
            ])
            ->add('description')
            ->add('max_capacity')
            ->add('price')
            ->add('room_count')
            ->add('bathroom_count')
            ->add('options', EntityType::class, [
                'class' => RentalOption::class,
                'choice_label' => function (?RentalOption $rentalOption) {
                    return $rentalOption ? $this->translator->trans($rentalOption->getName()) : '';
                },
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('system', ChoiceType::class, [
                'choices' => array_flip(Systems::getValuesOrderedByTranslation($this->translator))
            ])
            ->add('celestial_object', ChoiceType::class, [
                'choices' => array_flip(CelestialObjects::getValuesOrderedByTranslation($this->translator))
            ])
            ->add('longitude')
            ->add('latitude')
            ->add('transports', EntityType::class, [
                'class' => Transport::class,
                'choice_label' => function (?Transport $transport) {
                    return $transport ? $this->translator->trans($transport->getName()) : '';
                },
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'delete_image',
                'download_label' => 'download_image',
                'download_uri' => true,
                'image_uri' => true,
                'asset_helper' => true,
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
