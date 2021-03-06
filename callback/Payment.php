<?php

namespace HelloAsso\Callback;

require_once __DIR__.'/../Callback.php';
require_once __DIR__.'/../Resource.php';

use HelloAsso\Callback;
use HelloAsso\Resource;

/**
 * Notification concernant un paiement
 * 
 * @author fraoult
 * @license MIT
 */
class Payment extends Callback
{
	const STATUS_AUTHORIZED = 'AUTHORIZED';
	
	/** @var string    */ public $id;
	/** @var \DateTime */ public $date;
	/** @var string    */ public $amount;
	/** @var string    */ public $type;
	/** @var string    */ public $url;
	/** @var string    */ public $payer_first_name;
	/** @var string    */ public $payer_last_name;
	/** @var string    */ public $url_receipt;
	/** @var string    */ public $url_tax_receipt;
	/** @var string    */ public $action_id;
	/** @var string    */ public $status;
	/** @var string    */ public $mean;
	/** @var string    */ public $id_api_partner;
	/** @var string    */ public $url_called;
	
	/** @var \HelloAsso\Resource\Action  */ protected $action;
	/** @var \HelloAsso\Resource\Payment */ protected $payment;
	
	public function __construct()
	{
		parent::__construct();
				
		$this->id               = $this->getParam('id');
		$this->amount           = $this->getParam('amount');
		$this->type             = $this->getParam('type');
		$this->url              = $this->getParam('url');
		$this->payer_first_name = $this->getParam('payer_first_name');
		$this->payer_last_name  = $this->getParam('payer_last_name');
		$this->url_receipt      = $this->getParam('url_receipt');
		$this->url_tax_receipt  = $this->getParam('url_tax_receipt');
		$this->action_id        = $this->getParam('action_id');
		
		$date = $this->getParam('date');
		if (!empty($date))
		{
			$this->date = new \DateTime($date);
		}
	}
	
	/**
	 *
	 * @return \HelloAsso\Resource\Action
	 */
	public function getAction()
	{
		if (empty($this->action) && isset($this->action_id))
		{
		    $this->action = Resource\Action::get($this->action_id);
		}
		return $this->action;
	}
	
	/**
	 *
	 * @return \HelloAsso\Resource\Payment
	 */
	public function getPayment()
	{
		if (empty($this->payment))
		{
		    $this->payment = Resource\Payment::get($this->id);
		}
		return $this->payment;
	}
}
