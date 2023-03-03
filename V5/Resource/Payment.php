<?php

namespace HelloAsso\V5\Resource;


use HelloAsso\V5\Api\Response;
use HelloAsso\V5\Resource;
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


    const STATE_PENDING = 'Pending';
    const STATE_AUTHORIZED = 'Authorized';
    const STATE_REFUSED = 'Refused';
    const STATE_UNKNOWN = 'Unknown';
    const STATE_REGISTERED = 'Registered';
    const STATE_PROCESEED = 'Processed';


    /** @var string    */ public $id;
    /** @var string    */ public $cashOutState;
    /** @var string    */ public $paymentReceiptUrl;
    /** @var string    */ public $fiscalReceiptUrl;
    /** @var float     */ public $amount;
	/** @var \DateTime */ public $date;
    /** @var string    */ public $paymentMeans;
    /** @var int       */ public $installmentNumber;
    /** @var string    */ public $state;

    /** @var Order    */ public  $order;
    /** @var Payer    */ public  $payer;
    /** @var Item[]   */ public  $items = [];

	public function __construct($json)
	{
        parent::__construct($json);
        $this->installmentNumber = (int)$this->installmentNumber;
        $this->amount = $this->amount/100;
        $this->convert($this->order, Order::class);
        $this->convert($this->payer, Payer::class);
        $this->convert($this->items, Item::class);
	}

    public static function getAllFromForm($formType, $formSlug)
    {
        return Resource\Query\Payment::createFromResource(static::class)
            ->searchFromForm($formType, $formSlug)
            ->throwException()
            ->setResourceClass(static::class)
            ->getCollection()
            ;
    }

    public static function getAllFromOrganization()
    {
        return Resource\Query\Payment::createFromResource(static::class)
            ->searchFromOrganization()
            ->throwException()
            ->setResourceClass(static::class)
            ->getCollection()
            ;
    }

    /**
     * @return Query\PaymentRefund
     */
    public function refunder()
    {
        return Resource\Query\PaymentRefund::instanciate($this);
    }

    /**
     * @param string $comment
     * @param bool   $refundOrder
     * @param bool   $sendmail
     * @param string $authorization
     * @return Response
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
