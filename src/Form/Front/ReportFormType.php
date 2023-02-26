<?php

namespace App\Form\Front;

use App\Entity\Rental;
use App\Entity\RentalOption;
use App\Entity\Report;
use App\Entity\Transport;
use App\Enums\RentalTypes;
use App\Enums\ReportTypes;
use App\FakeApi\CelestialObjects;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ReportFormType extends AbstractType
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('comment', TextareaType::class, [
                'label' => 'comment',
                'required' => true,
            ])
            ->add('type', EnumType::class, [
                'label' => 'report_type',
                'required' => true,
                'class' => ReportTypes::class,
                'choice_label' => fn ($choice) => $this->translator->trans('report_'.ReportTypes::getValues()[$choice->value]),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Report::class,
        ]);
    }
}
