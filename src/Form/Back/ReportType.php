<?php

namespace App\Form\Back;

use App\Entity\Rental;
use App\Entity\Report;
use App\Entity\User;
use App\Enums\ReportTypes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ReportType extends AbstractType
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('comment')
            ->add('type', EnumType::class, [
                'label' => 'report_type',
                'required' => true,
                'class' => ReportTypes::class,
                'choice_label' => fn ($choice) => $this->translator->trans('report_'.ReportTypes::getValues()[$choice->value]),
            ])
            ->add('rental', EntityType::class, [
                'class' => Rental::class,
                'choice_label' => fn(Rental $rental) => $rental->getId() . ' - ' . $rental->getRentType()->value,
            ])
            ->add('author', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
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
