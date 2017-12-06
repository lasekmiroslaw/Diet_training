<?php

namespace AppBundle\Form;

use AppBundle\Entity\UserStrengthTrainingCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use AppBundle\Form\UserTrainingForm;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CollectionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('seriesTraining', CollectionType::class, array(
            'entry_type' => UserTrainingForm::class,
            'entry_options' => array('label' => false),
        	'allow_add' => true,
            'by_reference' => false,
            'allow_delete' => true,
        ));
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UserStrengthTrainingCollection::class,
        ));
    }
}
