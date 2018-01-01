<?php
namespace AppBundle\Form;

use AppBundle\Entity\UserData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class ChangeUserData extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('age', BirthdayType::class, array(
                'label' => 'Data urodzenia',
                'choice_translation_domain' => true,
                'format' => 'dd-MM-yyy',
            ))
            ->add('weight', NumberType::class, array('label' => false, 'attr' => array('class' => 'narrowFields', )))
            ->add('height', NumberType::class, array('label' => false, 'attr' => array('class' => 'narrowFields', )))
            ->add('calories', NumberType::class, array('label' => false, 'attr' => array('class' => 'narrowFields', )));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UserData::class,
        ));
    }
}
