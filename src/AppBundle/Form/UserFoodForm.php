<?php

namespace AppBundle\Form;

use AppBundle\Entity\UserFood;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UserFoodForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('productId', HiddenType::class)
            ->add('quantity', NumberType::class, array('label' => 'Ilość'))
            ->add('meal', ChoiceType::class, array('label' => 'Posiłek', 'choices'  => array(
                'Śniadanie' => 'sniadanie',
                'Lunch' => 'lunch',
                'Obiad' => 'obiad',
                'Kolacja' => 'kolacja',
                'Przekąski' => 'przekaski',
                'Inne' => 'inne')))
            ->add('add', SubmitType::class, array('label' => 'Dodaj produkt'))
            ->add('calories', NumberType::class, array('label' => false, 'scale' => 1))
            ->add('totalProtein', NumberType::class, array('label' => false, 'scale' => 1))
            ->add('fat', NumberType::class, array('label' => false, 'scale' => 1))
            ->add('carbohydrates', NumberType::class, array('label' => false, 'scale' => 1));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UserFood::class,
        ));
    }
}
