<?php

namespace AppBundle\Form;

use AppBundle\Entity\PickedDate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormInterface;

class DateForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder->add('pickedDate', DateType::class, array(
		    'widget' => 'single_text',
		    'html5' => false,
            'label' => false,
		));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => PickedDate::class,
        ));
	}
}
