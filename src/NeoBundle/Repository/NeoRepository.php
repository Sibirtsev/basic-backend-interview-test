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
}