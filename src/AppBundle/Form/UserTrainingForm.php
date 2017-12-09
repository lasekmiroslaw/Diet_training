<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use AppBundle\Entity\UserStrengthTraining;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class UserTrainingForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('kgLoad', NumberType::class, array('label' => 'ciężar[kg]', 'scale' => 2, 'required' => false))
            ->add('reps', IntegerType::class, array('label' => 'powtórzenia', 'required' => false))
            ->add('series', IntegerType::class, array('label' => 'seria', 'required' => false));
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UserStrengthTraining::class,
        ));
    }
}
