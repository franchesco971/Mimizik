<?php

namespace Spicy\SiteBundle\Form;

use Spicy\SiteBundle\Entity\Approval;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class ApprovalType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('approvalDate')
//            ->add('disapprovalDate')
            ->add('title', ApprovalVideoType::class, array(
                'constraints' => new Valid(),
            ))
//            ->add('user')
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
        return 'spicy_sitebundle_approval';
    }
}
