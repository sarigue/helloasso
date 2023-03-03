<?php

namespace HelloAsso\V5\Resource;


use HelloAsso\V5\Resource;
use HelloAsso\V5\Resource\Meta\User;
use HelloAsso\V5\Traits\Queryable;
use HelloAsso\V5\Traits\Response\Meta;

/**
 * Paiement HelloAsso
 * 
 * @author fraoult
 * @license MIT
 */
class Item extends Resource
{
    use Queryable, Meta;

    const RESOURCE_NAME = 'items';


    /** @var int       */ public $id;
    /** @var User      */ public $user;
    /** @var string    */ public $priceCategory;
    /** @var string    */ public $qrCode;
    /** @var string    */ public $membershipCardUrl;
    /** @var string    */ public $tierDescription;
    /** @var float     */ public $initialAmount;
    /** @var float     */ public $shareAmount;
    /** @var float     */ public $shareItemAmount;
    /** @var float     */ public $amount;
    /** @var string    */ public $type;
    /** @var string    */ public $state;

    public function __construct($json)
    {
        parent::__construct($json);
        $this->shareAmount = $this->shareAmount / 100;
        $this->shareItemAmount = $this->shareItemAmount / 100;
        $this->amount = $this->amount / 100;
        $this->initialAmount = $this->initialAmount / 100;
    }
    /**
     * @return $this
     */
    public function refresh()
    {
        $this->__construct(
            static::getResponse($this->id)
                ->getData()
        );
        return $this;
    }

}
