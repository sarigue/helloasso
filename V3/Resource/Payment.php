<?php

namespace HelloAsso\V3\Resource;

use HelloAsso\V3\Traits\ModelGetter;
use HelloAsso\V3\Resource;

/**
 * Paiement HelloAsso
 * 
 * @author fraoult
 * @license MIT
 */
class Payment extends Resource
{
    use ModelGetter;

    const RESOURCE_NAME = 'payments';
    
	const MEAN_CB    = 'CB';
	const MEAN_CARD  = 'CARD';
	const MEAN_CHECK = 'CHECK';
	const MEAN_SEAP  = 'SEPA';
	
	const TYPE_CREDIT = 'CREDIT';
	
	const STATUS_AUTHORIZED = 'AUTHORIZED';
	
	/** @var string    */ public $id;
	/** @var \DateTime */ public $date;
	/** @var float     */ public $amount;
	/** @var string    */ public $type;
	/** @var string    */ public $mean;
	/** @var string    */ public $payer_first_name;
	/** @var string    */ public $payer_last_name;
	/** @var string    */ public $payer_address;
	/** @var string    */ public $payer_zip_code;
	/** @var string    */ public $payer_city;
	/** @var string    */ public $payer_country;
	/** @var string    */ public $payer_email;
	/** @var \DateTime */ public $payer_birthdate;
	/** @var string    */ public $payer_citizenship;
	/** @var string    */ public $payer_society;
	/** @var boolean   */ public $payer_is_society;
	/** @var string    */ public $url_receipt;
	/** @var string    */ public $url_tax_receipt;
	/** @var string    */ public $status;
	/** @var PaymentAction[] */ public $actions = [];
	
	public function __construct($json)
	{
		foreach($json->actions as $action)
		{
			$this->actions[] = new PaymentAction($action);
		}
		unset($json->actions);
		parent::__construct($json);
		if (!empty($this->date))
		{
			$this->date = new \DateTime($this->date);
		}
		if (!empty($this->payer_birthdate))
		{
			$this->payer_birthdate = new \DateTime($this->payer_birthdate);
		}
	}
	
}
