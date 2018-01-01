<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class MessageGenerator
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function addProductMessage()
    {
        $this->session->set('alert', 'alert-success');
        $this->session->remove('meal');
        $message = 'Produkt dodany!';

        return $message;
    }

    public function removeProductMessage()
    {
        $this->session->set('alert', 'alert-danger');
        $message = 'Produkt usunięty!';

        return $message;
    }

    public function addTrainingMessage()
    {
        $this->session->set('alert', 'alert-success');
        $message = 'Trening dodany!';

        return $message;
    }

    public function removeTrainingMessage()
    {
        $this->session->set('alert', 'alert-danger');
        $message = 'Trening usunięty!';

        return $message;
    }
}
