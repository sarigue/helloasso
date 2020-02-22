<?php
namespace HelloAsso;

require_once __DIR__.'/../api/Query.php';
require_once __DIR__.'/../Resource.php';

use HelloAsso\Api\Query;

/**
 * Méthode de récupération
 * 
 * @author fraoult
 * @license MIT
 */
trait ModelGetter
{
    /**
     * @param string $id
     * @return \HelloAsso\Resource
     */
    public static function get($id)
    {
        return Query::create(static::RESOURCE_NAME, $id)
        ->execute()
        ->throwException()
        ->getResource(static::class);
    }
    
    /**
     * @param \HelloAsso\Api\Pagination & $pagination
     * @return \HelloAsso\Resource[]
     */
    public static function getAll(&$pagination = NULL)
    {
        $result = Query::create(static::RESOURCE_NAME)->execute()->throwException();
        $pagination = $result->getPagination();
        return $result->getResource(static::class);
    }
    
}
