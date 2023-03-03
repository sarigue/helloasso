<?php

namespace HelloAsso\V5\Resource;


use HelloAsso\V5\Resource;
use HelloAsso\V5\Resource\Meta\Banner;
use HelloAsso\V5\Resource\Meta\Tiers;
use HelloAsso\V5\Traits\Queryable;
use HelloAsso\V5\Traits\Response\Meta;

/**
 * Paiement HelloAsso
 * 
 * @author fraoult
 * @license MIT
 */
class Organization extends Resource
{
    use Queryable, Meta;

    const RESOURCE_NAME = 'organizations';

    const TYPE_CHECKOUT = 'Checkout';
    const TYPE_DONATION = 'Donation';
    const TYPE_EVENT = 'Event';
    const TYPE_MEMBERSHIP = 'Membership';
    const TYPE_PAYMENTFORM = 'PaymentForm';


    /** @var bool      */ public $isAuthenticated;
    /** @var bool      */ public $fiscalReceiptEligibility;
    /** @var bool      */ public $fiscalReceiptIssuanceEnabled;
    /** @var string    */ public $type;
    /** @var string    */ public $category;
    /** @var string    */ public $rnaNumber;
    /** @var string    */ public $logo;
    /** @var string    */ public $name;
    /** @var string    */ public $role;
    /** @var string    */ public $city;
    /** @var string    */ public $zipCode;
    /** @var string    */ public $description;
    /** @var string    */ public $url;
    /** @var string    */ public $organizationSlug;

    public function __construct($json)
    {
        parent::__construct($json);
        if ($this->logo)
        {
            $this->logo = str_replace(' ', '%20', $this->logo);
        }
    }

    /**
     * @see Queryable::refresh()
     * @return $this
     */
    public function refresh()
    {
        $this->__construct(
            static::getResponse($this->organizationSlug)
                ->getData()
        );
        return $this;
    }

}
