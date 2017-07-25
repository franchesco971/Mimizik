<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Spicy\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as baseType;

class RegistrationFormType extends AbstractType
{
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('groups', 'entity', array(
                'class'    => 'SpicyUserBundle:Group',
                'property' => 'name',
                'multiple' => false,
                'required'=>true
                )
            )
        ;
    } 
   
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention'  => 'registration',
        ));
    }
    
    public function getParent() {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'mimizik_user_registration';
    }
}
