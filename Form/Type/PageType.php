<?php

namespace Bafford\SimpleCmsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Validator\Constraints as Assert;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	if(!$options['requiredPage'])
    	    $builder->add($builder->create('slug', 'text', ['constraints' => [new Assert\NotBlank()]]));
    	
        $builder
            ->add('url')
            ->add('title')
            ->add('content')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bafford\SimpleCmsBundle\Entity\Page'
        ));
        
        $resolver->setRequired(['requiredPage']);
    }

    public function getName()
    {
        return 'bafford_simplecms_pagetype';
    }
}
