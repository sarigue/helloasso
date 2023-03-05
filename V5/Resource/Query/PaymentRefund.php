<?php

namespace HelloAsso\V5\Resource\Query;

use HelloAsso\V5\Api\Response;
use HelloAsso\V5\Api\ResponseError;
use HelloAsso\V5\Resource\Payment as PaymentResource;

class PaymentRefund extends Payment
{

    protected $payment_id;

    /**
     * @param PaymentResource $payment
     * @return PaymentRefund
     * @throws ResponseError
     */
    public static function instanciate(PaymentResource $payment)
    {
        $instance = static::create();
        $instance->payment_id = $payment->id;
        return $instance;
    }

    public function setComment($comment)
    {
        return $this->addParam('comment', $comment);
    }

    public function refundOrder($refund = true)
    {
        return $this->addParam('refundOrder', $refund);
    }

    public function sendMail($sendmail = true)
    {
        return $this->addParam('sendRefundMail', $sendmail);
    }

    /**
     * @return Response
     * @throws ResponseError
     */
    public function refund()
    {
        $route = $this->resource_path . '/' . $this->payment_id . '/refund';
        return $this->execute($route);
    }

}