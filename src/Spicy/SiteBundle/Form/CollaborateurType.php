<?php

namespace Spicy\SiteBundle\Form;

use Spicy\SiteBundle\Entity\Collaborateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CollaborateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('twitter', TextType::class)
            ->add('instagram', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Collaborateur::class
        ));
    }

    public function getName()
    {
        return 'spicy_sitebundle_collaborateurtype';
    }
}
