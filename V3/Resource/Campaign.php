<?php

namespace HelloAsso\V3\Resource;

use DateTime;
use Exception;
use HelloAsso\V3\Traits\ModelGetter;
use HelloAsso\V3\Resource;

/**
 * Campagne HelloAsso
 * 
 * @author fraoult
 * @license MIT
 */
class Campaign extends Resource
{
    use ModelGetter;
    
    const RESOURCE_NAME = 'campaigns';
    
	const TYPE_EVENT        = 'EVENT';
	const TYPE_FORM         = 'FORM';
	const TYPE_MEMBERSHIP   = 'MEMBERSHIP';
	const TYPE_FUNDRAISER   = 'FUNDRAISER';
	const TYPE_PAYMENT_FORM = 'PAYMENT_FORM';
	
	const STATE_PUBLIC   = 'PUBLIC';
	const STATE_PRIVATE  = 'PRIVATE';
	const STATE_DISABLED = 'DISABLED';
	const STATE_DRAFT    = 'DRAFT';
	
	/** @var string      */ public $id;
	/** @var string      */ public $name;
	/** @var string      */ public $slug;
	/** @var string      */ public $type;
	/** @var string      */ public $state;
	/** @var float       */ public $funding;
	/** @var integer     */ public $supporters;
	/** @var string      */ public $url;
	/** @var string      */ public $id_organism;
	/** @var string      */ public $slug_organism;
	/** @var DateTime   */ public $creation_date;
	/** @var DateTime   */ public $last_update;
	/** @var string      */ public $place_name;
	/** @var string      */ public $place_address;
	/** @var string      */ public $place_city;
	/** @var string      */ public $place_zipcode;
	/** @var string      */ public $place_country;
	/** @var DateTime   */ public $start_date;
	/** @var DateTime   */ public $end_date;

    /**
     * @throws Exception
     */
    public function __construct($json)
	{
		parent::__construct($json);
		foreach(['creation_date', 'last_update', 'start_date', 'end_date'] as $field)
		{
			if (!empty($this->$field))
			{
				$this->$field = new DateTime($this->$field);
			}
		}
	}
	
	
}