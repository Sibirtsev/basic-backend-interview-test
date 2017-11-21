<?php

namespace NeoBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;

class DefaultController extends FOSRestController
{
    /**
     * @Route("/")
     * @View(serializerGroups={"dummy"})
     */
    public function indexAction()
    {
        return ['hello' => 'world'];
    }
}
