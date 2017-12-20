<?php

namespace AppBundle\Twig;

class IdFilter extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('onlyId', array($this, 'idFilter')),
        );
    }

    public function idFilter($value)
    {
        $matches;
        if (preg_match('/^-[0-9]{1,6}-*/', $value, $matches)) {
            return preg_replace('/-/', '', $matches[0]);
        }
    }
}
