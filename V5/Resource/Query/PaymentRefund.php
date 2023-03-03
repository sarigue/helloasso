<?php

namespace HelloAsso\V5\Resource\Query;

use \HelloAsso\V5\Resource\Payment as PaymentResource;
class PaymentRefund extends Payment
{

    protected $payment_id;

    /**
     * @param PaymentResource $payment
     * @return PaymentRefund
     */
    public static function instanciate(PaymentResource $payment)
    {
        $instance = static::createFromResource(get_class($payment));
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
     * @return \HelloAsso\V5\Api\Response
     * @throws \HelloAsso\V5\Api\ResponseError
     */
    public function refund()
    {
        $route = $this->resource_path . '/' . $this->payment_id . '/refund';
        return $this->execute($route);
    }

}