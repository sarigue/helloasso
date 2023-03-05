<?php

namespace HelloAsso\V5\Resource;


use Exception;
use HelloAsso\V5\Api\ResponseError;
use HelloAsso\V5\Resource;
use HelloAsso\V5\Resource\Data\CustomField;
use HelloAsso\V5\Resource\Data\Payer;
use HelloAsso\V5\Resource\Data\User;
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

    const STATE_UNKNOWN    = 'Unknown';
    const STATE_REGISTERED = 'Registered';
    const STATE_PROCESEED  = 'Processed';
    const STATE_CANCELED   = 'Canceled';

    const TYPE_DONATION         = 'Donation';
    const TYPE_PAYMENT          = 'Payment';
    const TYPE_REGISTRATION     = 'Registration';
    const TYPE_MEMBERSHIP       = 'Membership';
    const TYPE_MONTHLYDONATION  = 'MonthlyDonation';
    const TYPE_MONTHLYPAYMENT   = 'MonthlyPayment';
    const TYPE_OFFLINEDONATION  = 'OfflineDonation';
    const TYPE_CONTRIBUTION     = 'Contribution';
    const TYPE_BONUS            = 'Bonus';
    const TYPE_PRODUCT          = 'Product';


    /** @var int         */ public $id;
    /** @var User        */ public $user;
    /** @var string      */ public $priceCategory;
    /** @var string      */ public $qrCode;
    /** @var string      */ public $membershipCardUrl;
    /** @var string      */ public $tierDescription;
    /** @var float       */ public $initialAmount;
    /** @var float       */ public $shareAmount;
    /** @var float       */ public $shareItemAmount;
    /** @var float       */ public $amount;
    /** @var string      */ public $type;
    /** @var string      */ public $state;
    /** @var Order       */ public $order;
    /** @var Payer       */ public $payer;
    /** @var Payment     */ public $payment;
    /** @var CustomField */ public $customFields;

    public function __construct($json)
    {
        parent::__construct($json);
        $this->shareAmount = $this->shareAmount / 100;
        $this->shareItemAmount = $this->shareItemAmount / 100;
        $this->amount = $this->amount / 100;
        $this->initialAmount = $this->initialAmount / 100;
        $this->convert($this->order, Order::class);
        $this->convert($this->payer, Payer::class);
        $this->convert($this->payment, Payment::class);
        $this->convert($this->customFields, CustomField::class);
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
