<?php
namespace HelloAsso\V5\Traits;

use HelloAsso\V5\Api\Pagination;
use HelloAsso\V5\ResourceQuery;

/**
 * Construction d'une requête d'API
 * 
 * @author fraoult
 * @license MIT
 * @const BASE_ROUTE
 */
trait Queryable
{

    /**
     * Refresh data from query result
     * @return $this
     */
    abstract function refresh();

    /**
     * @param Pagination $pagination
     * @return static[]
     * @throws \HelloAsso\V5\Api\ResponseError
     */
    public static function getAll(Pagination &$pagination)
    {
        return ResourceQuery::createFromResource(static::class)
            ->search()
            ->setResourceClass(static::class)
            ->throwException()
            ->getCollection($pagination)
            ;
    }

    /**
     * @param $id
     * @return static
     * @throws \HelloAsso\V5\Api\ResponseError
     */
    public static function get($id)
    {
        return static::getResponse($id)
            ->setResourceClass(static::class)
            ->getResource()
            ;
    }

    protected static function getResponse($id)
    {
        return ResourceQuery::createFromResource(static::class)
            ->get($id)
            ->throwException();
    }
}
