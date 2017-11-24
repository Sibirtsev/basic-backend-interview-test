<?php

namespace NeoBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class NeoRepository extends DocumentRepository
{
    public function findFastest($isHazardous = false)
    {
        $query = $this->createQueryBuilder()
            ->field('isHazardous')
            ->equals($isHazardous)
            ->sort('speed', 'DESC')
            ->limit(1)
            ->getQuery();

        return $query->execute()->toArray();
    }

    public function getBestYear($isHazardous)
    {
        $builder = $this->createAggregationBuilder();
        $builder
            ->match()
                ->field('isHazardous')
                ->equals($isHazardous)
            ->group()
                ->field('id')
                ->expression($builder->expr()->year('$date'))
                ->field('count')
                ->sum(1)
            ->sort(['count' => 'DESC'])
            ->limit(1)
            ->project()
                ->excludeFields(['count'])
                ->field('id')
        ;

        return $builder->execute()->toArray();
    }
}