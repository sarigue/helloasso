<?php

namespace HelloAsso\V5\Resource;


use DateTime;
use Exception;
use HelloAsso\V5\Api\ResponseError;
use HelloAsso\V5\Resource;
use HelloAsso\V5\Resource\Data\Amount;
use HelloAsso\V5\Resource\Data\Payer;
use HelloAsso\V5\Traits\Queryable;
use HelloAsso\V5\Traits\Response\Meta;

/**
 * Paiement HelloAsso
 * 
 * @author fraoult
 * @license MIT
 */
class Order extends Resource
{
    use Queryable, Meta;
    const RESOURCE_NAME = 'orders';

    /** @var string    */ public $id;
    /** @var DateTime */ public $date;
    /** @var string    */ public $formSlug;
    /** @var string    */ public $formType;
    /** @var string    */ public $organizationName;
    /** @var string    */ public $organizationSlug;
    /** @var string    */ public $formName;
    /** @var bool      */ public $isAnonymous;
    /** @var bool      */ public $isAmountHidden;

    /** @var Payer      */ public  $payer;
    /** @var Payment[]  */ public  $payments = [];
    /** @var Item[]     */ public  $items = [];
    /** @var Amount[]   */ public  $amounts = [];

    public function __construct($json)
    {
        parent::__construct($json);
        $this->convert($this->payer, Payer::class);
        $this->convert($this->payments, Payment::class);
        $this->convert($this->items, Item::class);
        $this->convert($this->amounts, Amount::class);
    }

    /**
     * @return $this
     * @throws ResponseError
     * @throws Exception
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
