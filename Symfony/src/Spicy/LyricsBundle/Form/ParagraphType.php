<?php

namespace Spicy\LyricsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Spicy\LyricsBundle\Entity\Paragraph;

class ParagraphType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('position','text')
            ->add('contentOriginal','textarea')
            ->add('contentTraduction','textarea')
            ->add('paragraph_type','choice',[
                'choices'=>[Paragraph::INTRO=>'Intro',Paragraph::COUPLET =>'Couplet',Paragraph::REFRAIN=>'Refrain',Paragraph::OUTRO=>'Outro']
            ])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
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
