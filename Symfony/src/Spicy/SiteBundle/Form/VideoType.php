<?php

namespace Spicy\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre','text')
            ->add('url','text')
            ->add('dateVideo','date')
            ->add('etat','checkbox')
            /*->add('artistes')
            ->add('genre_musicaux')
            ->add('type_videos')*/
            ->add('artistes', 'entity', array(
                'class'    => 'SpicySiteBundle:Artiste',
                'property' => 'libelle',
                'multiple' => true,
                'required'=>false)
            )
            ->add('genre_musicaux', 'entity', array(
                'class'    => 'SpicySiteBundle:GenreMusical',
                'property' => 'libelle',
                'multiple' => true,
                'required'=>false)
            )
            ->add('type_videos', 'entity', array(
                'class'    => 'SpicySiteBundle:TypeVideo',
                'property' => 'libelle',
                'multiple' => true)
            )
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Spicy\SiteBundle\Entity\Video'
        ));
    }

    public function getName()
    {
        return 'spicy_sitebundle_videotype';
    }
}
