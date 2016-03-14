<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class KlimaskaermType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('post')
            ->add('klimaskaerm')
            ->add('arbejdeOmfang')
            ->add('enhedsprisEksklMoms')
            ->add('enhed')
            ->add('noter')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Klimaskaerm'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_klimaskaerm';
    }
}
