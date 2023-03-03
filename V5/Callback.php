<?php

namespace HelloAsso\V5;


use HelloAsso\V5\Resource\Form;
use HelloAsso\V5\Resource\Order;
use HelloAsso\V5\Resource\Payment;

/**
 * Callback managment
 * 
 * @author fraoult
 * @license MIT
 */
class Callback
{

    const EVENT_TYPE_ORDER = 'Order';
    const EVENT_TYPE_PAYMENT = 'Payment';
    const EVENT_TYPE_FORM = 'Form';

	/** @var array    */ public $request;
    /** @var string   */ public $eventType;
    /** @var mixed    */ public $data;

	public function __construct()
	{
		$this->request = $_POST;
        $this->eventType = $this->getParam('eventType');
        $this->data = $this->getParam('data');
	}

    /**
     * @param string $type
     * @return bool
     */
    public function is($type)
    {
        return $this->eventType == $type;
    }

    /**
     * @return bool
     */
    public function isOrder()
    {
        return $this->is(static::EVENT_TYPE_ORDER);
    }

    /**
     * @return bool
     */
    public function isPayment()
    {
        return $this->is(static::EVENT_TYPE_PAYMENT);
    }

    /**
     * @return bool
     */
    public function isForm()
    {
        return $this->is(static::EVENT_TYPE_FORM);
    }

    public function getOrder()
    {
        $data = json_decode($this->data);
        if (!$data)
        {
            return null;
        }
        return $this->isOrder() ? new Order($data) : null;
    }

    public function getPayment()
    {
        $data = json_decode($this->data);
        if (!$data)
        {
            return null;
        }
        return $this->isPayment() ? new Payment($data) : null;
    }

    public function getForm()
    {
        $data = json_decode($this->data);
        if (!$data)
        {
            return null;
        }
        return $this->isForm() ? new Form($data) : null;
    }

    /**
	 * Récupère le paramètre $key depuis $_REQUEST ou NULL si inexistant
	 * @param string $key
	 * @return mixed
	 */
	protected function getParam($key)
	{
		return isset($_POST[$key]) ? $_POST[$key] : NULL;
	}
}
