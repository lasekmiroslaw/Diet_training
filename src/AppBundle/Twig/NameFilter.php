<?php

namespace AppBundle\Twig;

class NameFilter extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('name', array($this, 'nameFilter')),
        );
    }

    public function nameFilter($value)
    {
        $matches;
        if (preg_match('/^-[0-9]{1,6}-*/', $value, $matches)) {
            return str_replace($matches[0], '', $value) ;
        }

    }
}
