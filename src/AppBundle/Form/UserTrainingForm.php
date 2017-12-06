<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use AppBundle\Entity\UserStrengthTraining;

class UserTrainingForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('kgLoad', NumberType::class, array('label' => 'ciężar[kg]', 'scale' => 2,))
            ->add('reps', IntegerType::class, array('label' => 'powtórzenia',))
            ->add('series', IntegerType::class, array('label' => 'seria','data' => 1))
            ->add('exerciseId', HiddenType::class, array('attr' => array('class' => 'hidenExerciseId')));
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UserStrengthTraining::class,
        ));
    }
}
