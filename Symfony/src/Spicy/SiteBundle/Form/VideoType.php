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
            ->add('dateVideo','datetime')
            ->add('etat','checkbox',array(
                'required'=>false
            ))
            ->add('source','text')
            ->add('tags_fb','text', array('required' => false))
            ->add('tags_twitter','text', array('required' => false))
            ->add('description','textarea', array('required' => false))
            ->add('artistes', 'entity', array(
                'class'    => 'SpicySiteBundle:Artiste',
                'property' => 'libelle',
                'attr' => array('size' => 10),
                'multiple' => true,
                'required'=>false,
                'query_builder' => function(
                    \Doctrine\ORM\EntityRepository $er) {
                        return $er->createQueryBuilder('a')->orderBy('a.libelle', 'ASC');
                    }
                )
            )
            ->add('genre_musicaux', 'entity', array(
                'class'    => 'SpicySiteBundle:GenreMusical',
                'property' => 'libelle',
                'attr' => array('size' =>5),
                'multiple' => true,
                'required'=>false)
            )
            ->add('type_videos', 'entity', array(
                'class'    => 'SpicySiteBundle:TypeVideo',
                'property' => 'libelle',
                'attr' => array('size' => 5),
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
