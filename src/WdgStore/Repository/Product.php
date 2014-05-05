<?php
namespace WdgStore\Repository;

use Doctrine\ORM\EntityRepository;

class Product extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\Query
     */
    public function findByFeaturedProductsPaginationQuery()
    {
        return $this->createQueryBuilder("p")
                ->select("p")
                ->where("p.featured = 1")
                ->orderBy("p.created", "DESC")
                ->getQuery();
    }
    
    /**
     * @return \Doctrine\ORM\Query
     */
    public function findAllPaginationQuery()
    {
        return $this->createQueryBuilder("p")
                ->select("p")
                ->orderBy("p.name", "DESC")
                ->getQuery();
    }
}