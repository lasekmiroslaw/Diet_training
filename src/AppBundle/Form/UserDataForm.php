<?php

namespace AppBundle\Form;

use AppBundle\Entity\UserData;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UserDataForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('age', NumberType::class, array('label' => 'Wiek', 'invalid_message' => 'Proszę wprowadzić liczbę'))
            ->add('weight', NumberType::class ,array('label' => 'Waga', 'invalid_message' => 'Proszę wprowadzić liczbę'))
            ->add('height', NumberType::class, array('label' => 'Wzrost', 'invalid_message' => 'Proszę wprowadzić liczbę'))
            ->add('activity', ChoiceType::class, array('label' => 'Aktywność', 'choices'  => array(
                'leżący lub siedzący tryb życia/brak aktywności fizycznej' => 1,
                'praca siedząca/aktywność fizyczna na niskim poziomie' => 1.2,
                'praca nie fizyczna/trening 2 razy w tygodniu' => 1.4,
                'lekka praca fizyczna/trening 3-4 razy w tygodniu' => 1.6,
                'praca fizyczna/trening 5 razy w tygodniu' => 1.8,
                'ciężka praca fizyczna/codzienny trening' => 2)))
            ->add('gender', ChoiceType::class, array('label' => 'Płeć', 'choices'  => array(
                'mężczyzna' => 'mezczyzna',
                'kobieta' => 'kobieta',)))
            ->add('calories', NumberType::class, array('label' => 'Zapotrzebowanie kaloryczne', 'invalid_message' =>'Proszę wprowadzić liczbę'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UserData::class,
        ));
    }
}
