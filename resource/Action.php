<?php

namespace HelloAsso\Resource;

require_once __DIR__ . '/../Resource.php';

use HelloAsso\Resource;
use HelloAsso\ModelGetter;

/**
 * Action HelloAsso
 * 
 * @author fraoult
 * @license MIT
 */
class Action extends Resource
{
    use ModelGetter;
    
    const RESOURCE_NAME = 'actions';
    
	const STATUS_PROCESSED        = 'PROCESSED';
	
	const TYPE_DONATION           = 'DONATION';
	const TYPE_INSCRIPTION        = 'INSCRIPTION';
	const TYPE_SUBSCRIPTION       = 'RECURRENT_SUBSCRIPTION';
	const TYPE_RECURRENT_DONATION = 'RECURRENT_DONATION';
	const TYPE_OPTION             = 'OPTION';
	const TYPE_ADDITIONAL_OPTION  = 'ADDITIONAL_OPTION';
	
	/** @var string      */ public $id;
	/** @var string      */ public $id_campaign;
	/** @var string      */ public $id_organism;
	/** @var string      */ public $id_payment;
	/** @var \DateTime   */ public $date;
	/** @var float       */ public $amount;
	/** @var string      */ public $type;
	/** @var string      */ public $first_name;
	/** @var string      */ public $last_name;
	/** @var string      */ public $address;
	/** @var string      */ public $zip_code;
	/** @var string      */ public $city;
	/** @var string      */ public $country;
	/** @var string      */ public $email;
	/** @var string      */ public $status;
	/** @var string      */ public $citizenship;
	/** @var string      */ public $option_label;
	/** @var \stdClass[] */ public $custom_infos;
	
	
	/** @var \HelloAsso\Resource\Campaign  */ protected $campaign;
	/** @var \HelloAsso\Resource\Organism  */ protected $organism;
	/** @var \HelloAsso\Resource\Payment   */ protected $payment;
	
	
	public function __construct($json)
	{
		parent::__construct($json);
	}

	/**
	 * @return \HelloAsso\Resource\Campaign
	 */
	public function getCampaign()
	{
		if (empty($this->campaign))
		{
			$this->campaign = Campaign::get($this->id_campaign);
		}
		return $this->campaign;
	}

	/**
	 * @return \HelloAsso\Resource\Organism
	 */
	public function getOrganism()
	{
		if (empty($this->organism))
		{
			$this->organism = Organism::get($this->id_organism);
		}
		return $this->organism;
	}

	/**
	 * @return \HelloAsso\Resource\Payment
	 */
	public function getPayment()
	{
		if (empty($this->payment) && $this->id_payment)
		{
			$this->payment = Payment::get($this->id_payment);
		}
		return $this->payment;
	}
	
	/**
	 * Est-ce que cette action est liée à un paiement ?
	 * @return boolean
	 */
	public function hasPayment()
	{
		return !empty($this->id_payment);
	}
}