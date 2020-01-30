<?php

namespace Spicy\ITWBundle\Form;

use Spicy\ITWBundle\Entity\Interview;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Spicy\ITWBundle\Form\QuestionType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class InterviewType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class)
            ->add('active', CheckboxType::class, [
                'required' => false
            ])
            ->add('questions', CollectionType::class, [
                'label' => null,
                'entry_type'   => QuestionType::class,
                'allow_add'    => true,
                'allow_delete' => true
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Interview::class
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'spicy_itwbundle_interview';
    }
}
