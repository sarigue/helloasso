<?php

namespace HelloAsso\V3\Resource;

use Exception;
use HelloAsso\V3\Resource;

/**
 * Actions de la liste de paiement
 * 
 * @author fraoult
 * @license MIT
 */
class PaymentAction extends Resource
{
	/** @var string    */ public $id;
	/** @var string    */ public $type;
	/** @var float     */ public $amount;
	/** @var float     */ public $status;
	
	/** @var Action */ protected $action;

    /**
     * @return Action
     * @throws Exception
     */
	public function getAction()
	{
		if (empty($this->action))
		{
			$this->action = Action::get($this->id);
		}
		return $this->action;
	}
}
