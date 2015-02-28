<?php

namespace Spicy\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GenreMusicalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle','text')
            ->add('dateGenreMusical','datetime')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Spicy\SiteBundle\Entity\GenreMusical'
        ));
    }

    public function getName()
    {
        return 'spicy_sitebundle_genremusicaltype';
    }
}
