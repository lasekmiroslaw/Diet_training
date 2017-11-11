<?php

namespace AppBundle\Form;

use AppBundle\Entity\MyFood;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class MyFoodForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'Nazwa produktu'))
            ->add('calories', NumberType::class, array('label' => 'Kalorie w 100g'))
            ->add('totalProtein', NumberType::class, array('label' => 'Białko', 'scale' => 1, 'required' => false))
            ->add('carbohydrates', NumberType::class, array('label' => 'Węglowodany', 'scale' => 1, 'required' => false))
            ->add('fat', NumberType::class, array('label' => 'tłuszcze', 'scale' => 1, 'required' => false))
            ->add('water', NumberType::class, array('label' => 'woda', 'scale' => 1, 'required' => false))
            ->add('sodium', NumberType::class, array('label' => 'sód w mg', 'required' => false ))
            ->add('potassium', NumberType::class, array('label' => 'potas w mg', 'required' => false));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => MyFood::class,
        ));
    }
}
