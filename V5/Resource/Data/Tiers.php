<?php

namespace HelloAsso\V5\Resource\Data;

use HelloAsso\V5\Resource;

class Tiers extends Resource
{

    /** @var int    */ public $id;
    /** @var string */ public $label;
    /** @var string */ public $description;
    /** @var string */ public $tierType;
    /** @var float  */ public $price;
    /** @var float  */ public $vatRate;
    /** @var string */ public $paymentFrequency;
    /** @var int    */ public $maxPerUser;
    /** @var bool   */ public $isEligibleTaxReceipt;

    public function __construct($json)
    {
        parent::__construct($json);
        $this->price = $this->price / 100;
    }
}