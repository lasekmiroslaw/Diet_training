<?php

namespace AppBundle\Form\DataTransformer;

use AppBundle\Entity\Food;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FoodToNumberTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param  Food|null $food
     * @return string
     */
    public function transform($food)
    {
        if (null === $food) {
            return '';
        }

        return $food->getId();
    }

    /**
     * Transforms a string (number) to an object (food).
     *
     * @param  string $foodNumber
     * @return Food|null
     * @throws TransformationFailedException
     */
    public function reverseTransform($foodNumber)
    {
        if (!$foodNumber) {
            return;
        }

        $food = $this->em
            ->getRepository(Food::class)
            ->find($foodNumber)
        ;

        if (null === $food) {
            throw new TransformationFailedException(sprintf(
                'Produkt "%s" nie istnieje!',
                $foodNumber
            ));
        }

        return $food;
    }
}
