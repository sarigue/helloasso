<?php
namespace HelloAsso\V3\Traits;

use Exception;
use HelloAsso\V3\Api\Pagination;
use HelloAsso\V3\Api\Query;
use HelloAsso\V3\Resource;

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
     * @return static
     * @throws Exception
     * @noinspection PhpReturnDocTypeMismatchInspection
     */
    public static function get($id)
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return Query::create(static::RESOURCE_NAME, $id)
            ->setOrganismId(null)    // On a un ID : donc pas de précision Organisme / Campagne
            ->setOrganismSlug(null)  // On a un ID : donc pas de précision Organisme / Campagne
            ->setCampaignId(null)    // On a un ID : donc pas de précision Organisme / Campagne
            ->setPrivate()
            ->execute()
            ->throwException()
            ->getResource(static::class);
    }

    /**
     * @param Pagination & $pagination
     * @return Resource[]
     * @throws Exception
     * @throws Exception
     * @throws Exception
     */
    public static function getAll(&$pagination = NULL)
    {
        $result = Query::create(static::RESOURCE_NAME)->setPrivate()->execute()->throwException();
        $pagination = $result->getPagination();
        return $result->getResource(static::class);
    }
    
}
