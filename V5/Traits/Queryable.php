<?php
namespace HelloAsso\V5\Traits;

use Exception;
use HelloAsso\V5\Api\Pagination;
use HelloAsso\V5\Api\ResponseError;
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
     * @param mixed $options
     * @return $this
     */
    abstract function refresh($options = null);

    /**
     * @param Pagination $pagination
     * @return static[]
     * @throws ResponseError
     * @throws Exception
     * @noinspection PhpReturnDocTypeMismatchInspection
     */
    public static function getAll(Pagination &$pagination)
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
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
     * @throws ResponseError
     * @throws Exception
     * @noinspection PhpReturnDocTypeMismatchInspection
     */
    public static function get($id)
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return static::getResponse($id)
            ->setResourceClass(static::class)
            ->getResource()
            ;
    }

    /**
     * @throws ResponseError
     */
    protected static function getResponse($id, $params = [])
    {
        return ResourceQuery::createFromResource(static::class)
            ->setParams($params)
            ->get($id)
            ->throwException();
    }
}
