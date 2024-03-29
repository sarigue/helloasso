<?php

namespace HelloAsso\V3\Callback;

use DateTime;
use Exception;
use HelloAsso\V3\Callback;
use HelloAsso\V3\Resource\Campaign as ResourceCampaign;

/**
 * Notification concernant une campagne
 * 
 * @author fraoult
 * @license MIT
 */
class Campaign extends Callback
{
	const NOTIFICATION_TYPE_EDITION  = 'EDITION';
	const NOTIFICATION_TYPE_CREATION = 'CREATION';
	
	const TYPE_EVENT                   = 'EVENT';
	const TYPE_FORM                    = 'FORM';
	const TYPE_FUNDRAISER              = 'FUNDRAISER';
	const TYPE_MEMBERSHIP              = 'MEMBERSHIP';
	const TYPE_FUNDRAISER_PEER_TO_PEER = 'FUNDRAISER_PEER_TO_PEER' ;
	
	
	/** @var string    */ public $id;
	/** @var DateTime */ public $date;
	/** @var string    */ public $url;
	/** @var string    */ public $notification_type;
	/** @var string    */ public $type;
	
	/** @var ResourceCampaign */ protected $campaign;

    /**
     * @throws Exception
     */
    public function __construct()
	{
		parent::__construct();
				
		$this->id                = $this->getParam('id');
		$this->url               = $this->getParam('url');
		$this->notification_type = $this->getParam('notification_type');
		$this->type              = $this->getParam('type');
		$date = $this->getParam('date');
		if (!empty($date))
		{
			$this->date = new DateTime($date);
		}
	}

    /**
     * Récupère les informations sur la campagne
     * @return ResourceCampaign
     * @throws Exception
     */
	public function getCampaign()
	{
		return ResourceCampaign::get($this->id);
	}
	
}
