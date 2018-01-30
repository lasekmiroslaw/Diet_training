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
use AppBundle\Form\DataTransformer\FoodToNumberTransformer;

class UserFoodForm extends AbstractType
{
    private $transformer;

    public function __construct(FoodToNumberTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('productId', HiddenType::class, array('attr' => array('class' => 'hiddenType')))
            ->add('quantity', NumberType::class, array('label' => 'Ilość', 'attr' => array('class' => 'quantityField')))
            ->add('meal', ChoiceType::class, array('label' => 'Posiłek', 'choices'  => array(
                'Śniadanie' => 'sniadanie',
                'Lunch' => 'lunch',
                'Obiad' => 'obiad',
                'Kolacja' => 'kolacja',
                'Przekąski' => 'przekaski',
                'Inne' => 'inne')))
            ->add('add', SubmitType::class, array('label' => 'Dodaj produkt', 'attr' => array('class' => 'submitField')))
            ->add('calories', NumberType::class, array('label' => false, 'scale' => 1, 'attr' => array('class' => 'caloriesField')))
            ->add('totalProtein', NumberType::class, array('label' => false, 'scale' => 1, 'attr' => array('class' => 'proteinField')))
            ->add('fat', NumberType::class, array('label' => false, 'scale' => 1, 'attr' => array('class' => 'fatField')))
            ->add('carbohydrates', NumberType::class, array('label' => false, 'scale' => 1, 'attr' => array('class' => 'carbohydratesField')));
        
        $builder->get('productId')
            ->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UserFood::class,
        ));
    }
}
