<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('year',ChoiceType::class,[
                "choices" => [
                    2026 => 2026,
                    2027 => 2027
                ]
            ])
            ->add("month",ChoiceType::class,[
                "choices" => [
                'January' => 1,
                'February' => 2,
                'March' => 3,
                'April' => 4,
                'May' => 5,
                'June' => 6,
                'July' => 7,
                'August' => 8,
                'September' => 9,
                'October' => 10,
                'November' => 11,
                'December' => 12,
                ]
            ])
            ->add("submit",SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
