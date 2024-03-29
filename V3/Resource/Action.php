<?php

namespace HelloAsso\V3\Resource;

use DateTime;
use Exception;
use HelloAsso\V3\Traits\ModelGetter;
use HelloAsso\V3\Resource;
use stdClass;

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
	/** @var DateTime   */ public $date;
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
	/** @var stdClass[] */ public $custom_infos;
	
	
	/** @var Campaign */ protected $campaign;
	/** @var Organism */ protected $organism;
	/** @var Payment */ protected $payment;
	
	
	public function __construct($json)
	{
		parent::__construct($json);
	}

    /**
     * @return Campaign
     * @throws Exception
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
     * @return Organism
     * @throws Exception
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
     * @return Payment
     * @throws Exception
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