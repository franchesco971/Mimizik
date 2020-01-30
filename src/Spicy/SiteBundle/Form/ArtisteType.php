<?php

namespace Spicy\SiteBundle\Form;

use Spicy\SiteBundle\Entity\Artiste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ArtisteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextType::class)
            ->add('description', TextareaType::class)
            ->add('tag_facebook', TextType::class, array('required' => false))
            ->add('tag_twitter', TextType::class, array('required' => false))
            ->add('instagram', TextType::class, array('required' => false))
            ->add('hashtags', EntityType::class, array(
                'class'    => 'SpicyTagBundle:Hashtag',
                'choice_label' => 'libelle',
                'attr' => array('size' => 30),
                'multiple' => true,
                'required'=>false,
                'query_builder' => function(
                    \Doctrine\ORM\EntityRepository $er) {
                        return $er->createQueryBuilder('h')->orderBy('h.libelle', 'ASC');
                    }
                )
            )
            ->add('dateArtiste', DateTimeType::class,array(
                'label'=>'Date artiste'
            ))
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Artiste::class
        ));
    }

    public function getName()
    {
        return 'spicy_sitebundle_artistetype';
    }
}
