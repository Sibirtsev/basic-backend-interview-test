<?php

namespace NeoBundle\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use NeoBundle\Repository\NeoRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    /**
     * @Route("/neo/fastest", name="fastest")
     * @View(serializerGroups={"neo"})
     * @param Request $request
     * @return mixed
     */
    public function fastestAction(Request $request)
    {
        $isHazardous = $request->query->get('hazardous', 'false');
        $isHazardous = $isHazardous === 'true' ? true : false;

        /** @var DocumentManager $dm */
        $dm = $this->get('doctrine_mongodb')->getManager();

        /** @var NeoRepository $repository */
        $repository = $dm->getRepository('NeoBundle:Neo');

        $object = $repository->findFastest($isHazardous);

        return reset($object);
    }

    /**
     * @Route("/neo/best-year", name="best-year")
     * @param Request $request
     * @return mixed
     */
    public function bestYearAction(Request $request)
    {
        $isHazardous = $request->query->get('hazardous', 'false');
        $isHazardous = $isHazardous === 'true' ? true : false;

        /** @var DocumentManager $dm */
        $dm = $this->get('doctrine_mongodb')->getManager();

        /** @var NeoRepository $repository */
        $repository = $dm->getRepository('NeoBundle:Neo');

        $bestYear = $repository->getBestYear($isHazardous);
        if (empty($bestYear)) {
            throw new NotFoundHttpException();
        }
        return ['best-year' => $bestYear];
    }

    /**
     * @Route("/neo/best-month", name="best-month")
     * @param Request $request
     * @return mixed
     */
    public function bestMonthAction(Request $request)
    {
        $isHazardous = $request->query->get('hazardous', 'false');
        $isHazardous = $isHazardous === 'true' ? true : false;

        /** @var DocumentManager $dm */
        $dm = $this->get('doctrine_mongodb')->getManager();

        /** @var NeoRepository $repository */
        $repository = $dm->getRepository('NeoBundle:Neo');

        $bestMonth = $repository->getBestMonth($isHazardous);
        if (empty($bestMonth)) {
            throw new NotFoundHttpException();
        }
        return ['best-month' => $bestMonth];
    }
}
