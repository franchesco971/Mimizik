<?php

namespace Spicy\SiteBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * Description of AbstractRepository
 *
 * @author franciscopol
 */
class AbstractRepository extends EntityRepository 
{
    protected function paginate(QueryBuilder $qb, $limit = 20, $offset = 0)
    {
        if (0 == $limit || 0 == $offset) {
            throw new \LogicException('$limit & $offstet must be greater than 0.');
        }
        
        $pager = new Pagerfanta(new DoctrineORMAdapter($qb));
        $currentPage = ceil($offset + 1) / $limit;
        $pager->setCurrentPage($currentPage);
        $pager->setMaxPerPage((int) $limit);
        
        return $pager;
    }
    
    protected function paginateByPage(QueryBuilder $qb, $limit = 20, $page = 1)
    {
        if (0 == $limit || 0 == $page) {
            throw new \LogicException('$limit & $page must be greater than 0.');
        }
        
        $pager = new Pagerfanta(new DoctrineORMAdapter($qb));
        $pager->setCurrentPage($page);
        $pager->setMaxPerPage((int) $limit);
        
        return $pager;
    }
}
