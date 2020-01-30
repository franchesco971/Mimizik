<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spicy\SiteBundle\Form;

use Spicy\SiteBundle\Entity\Approval;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * Description of ApprovalAdminType
 *
 * @author franciscopol
 */
class ApprovalAdminType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', VideoType::class, array(
                'constraints' => new Valid()
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Approval::class
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'spicy_sitebundle_approval_admin';
    }
}
