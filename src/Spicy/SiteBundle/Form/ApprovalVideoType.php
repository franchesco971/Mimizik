<?php

namespace Spicy\SiteBundle\Form;

use Spicy\SiteBundle\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ApprovalVideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class,[
                'label'=>'Titre',
                'required'=>true
            ])
            ->add('url', TextType::class,[
                'label'=>'Id Youtube',
                'required'=>true
            ])
            ->add('tags_fb', TextType::class, ['label'=>'Tags facebook','required' => false])
            ->add('tags_twitter', TextType::class, array('required' => false))
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
            ->add('description', TextareaType::class, array('required' => false))
            ->add('artistes', EntityType::class, array(
                'class'    => 'SpicySiteBundle:Artiste',
                'choice_label' => 'libelle',
                'attr' => array('size' => 10),
                'multiple' => true,
                'required'=>false,
                'query_builder' => function(
                    \Doctrine\ORM\EntityRepository $er) {
                        return $er->createQueryBuilder('a')->orderBy('a.libelle', 'ASC');
                    }
                )
            )
            ->add('genre_musicaux', EntityType::class, array(
                'class'    => 'SpicySiteBundle:GenreMusical',
                'choice_label' => 'libelle',
                'attr' => array('size' =>5),
                'multiple' => true,
                'required'=>false)
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Video::class
        ));
    }

    public function getName()
    {
        return 'spicy_sitebundle_videotype';
    }
}
