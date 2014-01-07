<?php

namespace Spicy\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArtisteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle','text')
            ->add('description','textarea')
            ->add('tag_facebook','text', array('required' => false))
            ->add('tag_twitter','text', array('required' => false))
            ->add('hashtags','text', array('required' => false))
            ->add('dateArtiste','datetime')
            
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Spicy\SiteBundle\Entity\Artiste'
        ));
    }

    public function getName()
    {
        return 'spicy_sitebundle_artistetype';
    }
}
