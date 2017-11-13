<?php

namespace Spicy\LyricsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Spicy\LyricsBundle\Form\ParagraphType;

class LyricsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('paragraphs','collection',[
                'label'=>null,
                'type'   => new ParagraphType,
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
            'data_class' => 'Spicy\LyricsBundle\Entity\Lyrics'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'spicy_lyricsbundle_lyrics';
    }
}
