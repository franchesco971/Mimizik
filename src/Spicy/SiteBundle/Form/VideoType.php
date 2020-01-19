<?php

namespace Spicy\SiteBundle\Form;

use Spicy\SiteBundle\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url', TextType::class)
        ;

        if ($options["video"]) {
            $builder
                ->add('titre', TextType::class)
                // ->add('url', TextType::class)
                ->add('dateVideo', DateTimeType::class)
                ->add('etat', CheckboxType::class,array(
                    'required'=>false
                ))
                ->add('onTop', CheckboxType::class,array(
                    'required'=>false,
                    'label'=>'On top'
                ))
                ->add('source', TextType::class)
                ->add('tags_fb', TextType::class, array('required' => false))
                ->add('tags_twitter', TextType::class, array('required' => false))
                ->add('hashtags', EntityType::class, array(
                    'class'    => 'SpicyTagBundle:Hashtag',
                    'choice_label' => 'libelle',
                    'attr' => array('size' => 30),
                    'multiple' => true,
                    'required'=>false,
                    )
                )
                ->add('description', TextareaType::class, [
                    'required' => false, 
                    'attr' => ['rows' => 10,'cols' => 50]
                    ])
                ->add('artistes', EntityType::class, array(
                    'class'    => 'SpicySiteBundle:Artiste',
                    'choice_label' => 'libelle',
                    'attr' => array('size' => 10),
                    'multiple' => true,
                    'required'=>false
                    )
                )
                ->add('collaborateurs', EntityType::class, array(
                    'class'    => 'SpicySiteBundle:Collaborateur',
                    'choice_label' => 'name',
                    'attr' => array('size' => 10),
                    'multiple' => true,
                    'required'=>false
                    )
                )
                ->add('genre_musicaux', EntityType::class, array(
                    'class'    => 'SpicySiteBundle:GenreMusical',
                    'choice_label' => 'libelle',
                    'attr' => array('size' =>5),
                    'multiple' => true,
                    'required'=>false)
                )
                ->add('type_videos', EntityType::class, array(
                    'class'    => 'SpicySiteBundle:TypeVideo',
                    'choice_label' => 'libelle',
                    'attr' => array('size' => 5),
                    'multiple' => true)
                )
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Video::class,
            'video' => true
        ));
    }

    public function getName()
    {
        return 'spicy_sitebundle_videotype';
    }
}
