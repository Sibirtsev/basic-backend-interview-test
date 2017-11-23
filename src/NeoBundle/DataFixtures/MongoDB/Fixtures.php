<?php

namespace NeoBundle\DataFixtures\MongoDB;

use NeoBundle\Document\Neo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class Fixtures extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $fixtureKeys = [
            'date',
            'reference',
            'name',
            'speed',
            'is_hazardous'
        ];

        $fixturesData = [
            ['2016-05-01', 1,  '1',  10., true],
            ['2016-05-02', 2,  '2',  21., false],
            ['2016-05-04', 3,  '3',  42., true],
            ['2017-01-20', 4,  '4',  11., false],
            ['2017-01-20', 5,  '5',  22., true],
            ['2017-03-20', 6,  '6',  14., false],
            ['2017-03-20', 7,  '7',  19., true],
            ['2017-03-20', 8,  '8',  15., false],
            ['2017-03-20', 9,  '9',  51., true],
            ['2017-03-20', 10, '10', 34., false],
            ['2017-11-20', 11, '11', 23., true],
            ['2017-11-20', 12, '12', 17., false],
            ['2017-11-20', 13, '13', 9.,  true],
            ['2017-11-20', 14, '14', 38., false],
        ];

        foreach ($fixturesData as $data) {
            $fixture = array_combine($fixtureKeys, $data);
            $neo = new Neo();
            $neo->setDate(new \DateTime($fixture['date']));
            $neo->setReference($fixture['reference']);
            $neo->setName($fixture['name']);
            $neo->setSpeed($fixture['speed']);
            $neo->setIsHazardous($fixture['is_hazardous']);

            $manager->persist($neo);
        }
        $manager->flush();
    }
}