<?php

namespace Spicy\ITWBundle\Form;

use Spicy\ITWBundle\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class QuestionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('answer', TextareaType::class,[
                'attr'=>['cols'=>80,'rows'=>10]
            ])
            ->add('content', TextType::class,[
                'attr'=>['cols'=>80,'rows'=>10]
            ])
            ->add('position', TextType::class)
            ->add('main', CheckboxType::class,[
                'required'=>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Question::class
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'spicy_itwbundle_question';
    }
}
