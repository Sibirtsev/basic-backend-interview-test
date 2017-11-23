<?php

namespace NeoBundle\Command;

use NeoBundle\Document\Neo;
use NeoBundle\Repository\NeoRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NeoFetchDataCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('neo:fetch-data')
            ->setDescription('Request the data from the last 3 days from nasa api')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dm = $this->getContainer()->get('doctrine_mongodb')->getManager();
        /** @var NeoRepository $repository */
        $repository = $dm->getRepository('NeoBundle:Neo');

        $nasaService = $this->getContainer()->get('neo.nasa.service');
        $objects = $nasaService->fetchLastThreeDays();
        foreach ($objects as $object) {
            $exists = $repository->findOneBy([
                'date' => new \DateTime($object['date']),
                'reference' => $object['reference']
            ]);

            if ($exists) {
                continue;
            }

            $neo = new Neo();
            $neo->setDate(new \DateTime($object['date']));
            $neo->setReference($object['reference']);
            $neo->setName($object['name']);
            $neo->setSpeed($object['speed']);
            $neo->setIsHazardous($object['is_hazardous']);
            $dm->persist($neo);
        }
        $dm->flush();
    }
}
