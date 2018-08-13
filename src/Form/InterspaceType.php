<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class InterspaceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class, [
                'widget' => 'choice',
                'label' => false,
                'format' => 'ddMMyyyy',
                'years' => range(2013,2050),
                'placeholder' => [
                    'day' => 'День', 'month' => 'Месяц', 'year' => 'Год'
                ]
            ])
            ->add('finishDate', DateType::class, [
                'widget' => 'choice',
                'label' => false,
                'format' => 'ddMMyyyy',
                'years' => range(2013,2050),
                'placeholder' => [
                    'day' => 'День', 'month' => 'Месяц', 'year' => 'Год'
                ]
            ])
            ->add('price', NumberType::class, [
                'label' => 'Цена, грн',
                'scale' => 2
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Interspace'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_interspace';
    }
}