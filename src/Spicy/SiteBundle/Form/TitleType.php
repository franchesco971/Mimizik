<?php

namespace Spicy\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Spicy\LyricsBundle\Form\LyricsType;
use Spicy\SiteBundle\Entity\Title;

class TitleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lyrics',new LyricsType(),[
                'label'=>null
            ]);
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Title::class
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'spicy_sitebundle_title';
    }
}
