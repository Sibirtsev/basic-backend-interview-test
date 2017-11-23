<?php

namespace NeoBundle\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use NeoBundle\Repository\NeoRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;

class DefaultController extends FOSRestController
{
    /**
     * @Route("/", name="hello")
     * @View(serializerGroups={"dummy"})
     */
    public function indexAction()
    {
        return ['hello' => 'world'];
    }

    /**
     * @Route("/neo/hazardous", name="hazardous")
     * @View(serializerGroups={"neo"})
     */
    public function hazardousAction()
    {
        /** @var DocumentManager $dm */
        $dm = $this->get('doctrine_mongodb')->getManager();

        /** @var NeoRepository $repository */
        $repository = $dm->getRepository('NeoBundle:Neo');
        return $repository->findBy(['isHazardous' => true], ['date' => 1]);
    }
}
