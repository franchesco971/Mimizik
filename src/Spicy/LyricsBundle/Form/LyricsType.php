<?php

namespace Spicy\LyricsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Spicy\LyricsBundle\Form\ParagraphType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class LyricsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('paragraphs', CollectionType::class, [
                'label' => null,
                'entry_type'   => ParagraphType::class,
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
