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
        ->setOrganismId(NULL)    // On a un ID : donc pas de précision Organisme / Campagne
        ->setOrganismSlug(NULL)  // On a un ID : donc pas de précision Organisme / Campagne
        ->setCampaignId(NULL)    // On a un ID : donc pas de précision Organisme / Campagne
        ->setPrivate()
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
        $result = Query::create(static::RESOURCE_NAME)->setPrivate()->execute()->throwException();
        $pagination = $result->getPagination();
        return $result->getResource(static::class);
    }
    
}
