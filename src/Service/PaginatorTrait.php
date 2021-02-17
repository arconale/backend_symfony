<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\Query;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;

trait PaginatorTrait
{
    /**
     * @return int
     */
    public function getMaxPerPage()
    {
        return 10;
    }

    /**
     * Create Paginator
     *
     * @param Query    $query
     * @param int      $page
     * @param int|null $limit
     *
     * @return Pagerfanta
     */
    protected function createPaginator(Query $query, $page, $limit = null)
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage($limit ?: $this->getMaxPerPage());
        $paginator->setCurrentPage($page);
        return $paginator;
    }
}
