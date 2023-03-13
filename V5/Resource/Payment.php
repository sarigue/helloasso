<?php

namespace HelloAsso\V5\Resource;


use DateTime;
use Exception;
use HelloAsso\V5\Api\Response;
use HelloAsso\V5\Api\ResponseError;
use HelloAsso\V5\Resource;
use HelloAsso\V5\Resource\Data\Payer;
use HelloAsso\V5\Resource\Query\PaymentRefund;
use HelloAsso\V5\Traits\Queryable;
use HelloAsso\V5\Traits\Response\Meta;

/**
 * Paiement HelloAsso
 * 
 * @author fraoult
 * @license MIT
 */
class Payment extends Resource
{
    use Queryable, Meta;

    const RESOURCE_NAME = 'payments';


    const STATE_PENDING    = 'Pending';
    const STATE_AUTHORIZED = 'Authorized';
    const STATE_REFUSED    = 'Refused';
    const STATE_UNKNOWN    = 'Unknown';
    const STATE_REGISTERED = 'Registered';
    const STATE_REFUNDED   = 'Refunded';
    const STATE_REFUNDING  = 'Refunding';
    const STATE_CONTESTED  = 'Contested';

    const TYPE_OFFLINE = 'Offline';
    const TYPE_CREDIT  = 'Credit';
    const TYPE_DEBIT   = 'Debit';

    const MEAN_NONE  = 'None';
    const MEAN_CARD  = 'Card';
    const MEAN_SEPA  = 'Sepa';
    const MEAN_CHECK = 'Check';
    const MEAN_CASH  = 'Cash';
    const MEAN_BANK_TRANSFER = 'BankTransfer';


    /** @var string    */ public $id;
    /** @var DateTime */ public $cashOutDate;
    /** @var string    */ public $cashOutState;
    /** @var string    */ public $paymentReceiptUrl;
    /** @var string    */ public $fiscalReceiptUrl;
    /** @var float     */ public $amount;
    /** @var float     */ public $amountTip;
	/** @var DateTime */ public $date;
    /** @var string    */ public $paymentMeans;
    /** @var int       */ public $installmentNumber;
    /** @var string    */ public $paymentOffLineMean;
    /** @var string    */ public $state;
    /** @var string    */ public $type;

    /** @var Order    */ public  $order;
    /** @var Payer    */ public  $payer;
    /** @var Item[]   */ public  $items = [];

    /**
     * @throws Exception
     */
    public function __construct($json)
	{
        parent::__construct($json);
        $this->installmentNumber = (int)$this->installmentNumber;
        $this->amount = $this->amount / 100;
        $this->amountTip = $this->amountTip / 100;
        $this->cashOutDate = new DateTime($this->cashOutDate);
        $this->convert($this->order, Order::class);
        $this->convert($this->payer, Payer::class);
        $this->convert($this->items, Item::class);
	}

    /**
     * @throws ResponseError
     * @throws Exception
     */
    public static function getAllFromForm($formType, $formSlug)
    {
        return Query\Payment::create()
            ->searchFromForm($formType, $formSlug)
            ->throwException()
            ->getCollection()
            ;
    }

    /**
     * @throws ResponseError
     * @throws Exception
     */
    public static function getAllFromOrganization()
    {
        return Query\Payment::create()
            ->searchFromOrganization()
            ->throwException()
            ->getCollection()
            ;
    }

    /**
     * @return PaymentRefund
     * @throws ResponseError
     */
    public function refunder()
    {
        return Query\PaymentRefund::instanciate($this);
    }

    /**
     * @param string $comment
     * @param bool $refundOrder
     * @param bool $sendmail
     * @param string $authorization
     * @return Response
     * @throws ResponseError
     */
    public function refund(
        $comment = null,
        $refundOrder = null,
        $sendmail = null,
        $authorization = null
    )
    {
        return $this->refunder()
            ->setComment($comment)
            ->refundOrder($refundOrder)
            ->sendMail($sendmail)
            ->setAuthorization($authorization)
            ->refund();
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function refresh()
    {
        $this->__construct(
            static::getResponseForId($this->id)
            ->getData()
        );
        return $this;
    }


}
