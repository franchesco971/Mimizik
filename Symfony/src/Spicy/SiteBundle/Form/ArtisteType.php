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
            ->add('hashtags', 'entity', array(
                'class'    => 'SpicyTagBundle:Hashtag',
                'property' => 'libelle',
                'attr' => array('size' => 10),
                'multiple' => true,
                'required'=>false,
                'query_builder' => function(
                    \Doctrine\ORM\EntityRepository $er) {
                        return $er->createQueryBuilder('h')->orderBy('h.libelle', 'ASC');
                    }
                )
            )
            ->add('dateArtiste','datetime',array(
                'label'=>'Date artiste'
            ))
            
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
