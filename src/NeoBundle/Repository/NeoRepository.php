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
                ->expression($builder->expr()
                    ->field('year')->year('$date')
                )
                ->field('count')
                ->sum(1)
            ->sort(['count' => 'DESC'])
            ->limit(1)
            ->project()
                ->excludeFields(['count'])
                ->field('id')
        ;

        $result = $builder->execute()->toArray();
        $result = reset($result);

        if (!is_array($result) || !array_key_exists('_id', $result)) {
            return null;
        }
        return strval($result['_id']['year']);
    }

    public function getBestMonth($isHazardous)
    {
        $builder = $this->createAggregationBuilder();
        $builder
            ->match()
                ->field('isHazardous')
                ->equals($isHazardous)
            ->group()
            ->field('id')
                ->expression($builder->expr()
                    ->field('year')->year('$date')
                    ->field('month')->month('$date')
                )
                ->field('count')
                ->sum(1)
            ->sort(['count' => 'DESC'])
            ->limit(1)
            ->project()
                ->excludeFields(['count'])
                ->field('id')
        ;

        $result = $builder->execute()->toArray();
        $result = reset($result);

        if (!is_array($result) || !array_key_exists('_id', $result)) {
            return null;
        }

        return sprintf("%'04d-%'02d", $result['_id']['year'], $result['_id']['month']);
    }
}
