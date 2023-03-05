<?php

namespace HelloAsso\V3\Resource;

use HelloAsso\Api\Query;
use HelloAsso\V3\Resource;

/**
 * Campagne publique HelloAsso
 * 
 * @author fraoult
 * @license MIT
 */
class BasicCampaign extends Resource
{    
    const RESOURCE_NAME     = 'campaigns';
    
	const TYPE_EVENT        = 'EVENT';
	const TYPE_FORM         = 'FORM';
	const TYPE_MEMBERSHIP   = 'MEMBERSHIP';
	const TYPE_FUNDRAISER   = 'FUNDRAISER';
	
	/** @var string      */ public $name;
	/** @var string      */ public $slug;
	/** @var string      */ public $type;
	/** @var string      */ public $url;
	/** @var string      */ public $url_widget;
	/** @var string      */ public $url_button;
	/** @var string      */ public $name_organism;
	/** @var string      */ public $slug_organism;
	
	public function __construct($json)
	{
		parent::__construct($json);
	}
	
	/**
	 * Récupère les campagnes d'un organisme par son slug
	 * @param string $slug
	 * @param string $type
	 * @param integer $page
	 * @param integer $results_per_page
	 * @param Pagination & $pagination
	 * @return \HelloAsso\Resource\BasicCampaign[]
	 */
	public static function searchForOrganismSlug($slug, $type = NULL, $page = NULL, $results_per_page = NULL, &$pagination = NULL)
	{
	    $response = Query::create(static::RESOURCE_NAME)
	    ->setPage($page)
	    ->setResultsPerPage($results_per_page)
	    ->addParam('type', $type)
	    ->setOrganismSlug($slug)
	    ->setPublic()
	    ->execute()
	    ->throwException();
	    
	    $pagination = $response->getPagination();
	    return $response->getResource(static::class);
	}
	
	/**
	 * Récupère les campagnes d'un organisme par son ID
	 * @param string $organism_id
	 * @param string $type
	 * @param integer $page
	 * @param integer $results_per_page
	 * @param Pagination & $pagination
	 * @return \HelloAsso\Resource\BasicCampaign[]
	 */
	public static function searchForOrganismId($organism_id, $type = NULL, $page = NULL, $results_per_page = NULL, &$pagination = NULL)
	{
	    $response = Query::create(static::RESOURCE_NAME)
	    ->setPage($page)
	    ->setResultsPerPage($results_per_page)
	    ->addParam('type', $type)
	    ->setOrganismId($organism_id)
	    ->setPublic()
	    ->execute()
	    ->throwException();
	    
	    $pagination = $response->getPagination();
	    return $response->getResource(static::class);
	}
}