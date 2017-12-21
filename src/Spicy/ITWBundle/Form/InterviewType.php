<?php

namespace Spicy\ITWBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Spicy\ITWBundle\Form\QuestionType;

class InterviewType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text')
                ->add('active', 'checkbox',[
                    'required'=>false
                ])
                ->add('questions','collection',[
                'label'=>null,
                'type'   => new QuestionType,
                'allow_add'    => true,
                'allow_delete' => true
            ])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Spicy\ITWBundle\Entity\Interview'
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
