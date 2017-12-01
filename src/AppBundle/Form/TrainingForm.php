<?php

namespace AppBundle\Form;

use AppBundle\Entity\StrengthTraining;
use AppBundle\Form\ExerciseType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TrainingForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('exercises', CollectionType::class, array(
            'entry_type' => ExerciseType::class,
            'entry_options' => array('label' => false),
        	'allow_add' => true,
            'by_reference' => false,
            'allow_delete' => true,
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => StrengthTraining::class,
        ));
    }
}
