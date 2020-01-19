<?php

namespace Spicy\LyricsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Spicy\LyricsBundle\Entity\Paragraph;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ParagraphType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('position', TextType::class)
            ->add('contentOriginal', TextareaType::class, [
                'attr' => ['cols' => 80, 'rows' => 10]
            ])
            ->add('contentTraduction', TextareaType::class, [
                'attr' => ['cols' => 80, 'rows' => 10],
                'required' => false
            ])
            ->add('paragraph_type', ChoiceType::class, [
                'choices' => [
                    Paragraph::INTRO => 'Intro',
                    Paragraph::COUPLET => 'Couplet',
                    Paragraph::REFRAIN => 'Refrain',
                    Paragraph::OUTRO => 'Outro'
                ]
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Spicy\LyricsBundle\Entity\Paragraph'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'spicy_lyricsbundle_paragraph';
    }
}
