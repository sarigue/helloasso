<?php

namespace HelloAsso\Resource;

require_once __DIR__ . '/../Resource.php';

use HelloAsso\Resource;
use HelloAsso\ModelGetter;

/**
 * Organisme HelloAsso
 * 
 * @author fraoult
 * @license MIT
 */
class Organism extends Resource
{
    use ModelGetter;
    
    const RESOURCE_NAME = 'organizations';
    
	const TYPE_ORGANISM = 'ORGANISM';
	
	/** @var string      */ public $id;
	/** @var string      */ public $name;
	/** @var string      */ public $slug;
	/** @var string      */ public $type;
	/** @var float       */ public $funding;
	/** @var integer     */ public $supporters;
	/** @var string      */ public $logo;
	/** @var string      */ public $thumbnail;
	/** @var string      */ public $profile;
	/** @var string      */ public $donate_form;
		
	public function __construct($json)
	{
		parent::__construct($json);
	}
}