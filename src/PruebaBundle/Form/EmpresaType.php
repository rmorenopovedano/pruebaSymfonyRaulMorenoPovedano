<?php

namespace PruebaBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmpresaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nombre', TextType::class)
            ->add('telefono', TextType::class, array('required'=>false))
            ->add('email', EmailType::class,array('required'=>false))
            ->add('sector', EntityType::class, array(
                // query choices from this entity
                'class' => 'PruebaBundle:Sector',

                // use the User.username property as the visible option string
                'choice_label' => 'nombre',
                'choice_value' => 'id',

            ))
            ->add('save', SubmitType::class, array('label' => 'Guardar'))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PruebaBundle\Entity\Empresa'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'pruebabundle_empresa';
    }


}
