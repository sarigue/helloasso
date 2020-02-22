<?php

namespace HelloAsso;

use HelloAsso\Callback\Payment;
use HelloAsso\Callback\Campaign;

require_once __DIR__.'/traits/Testable.php';

/**
 * Methode abstraite callback
 * 
 * @author fraoult
 * @license MIT
 */
abstract class Callback
{
    use Testable;
	
	/** @var string   */ public $request;
		
	public function __construct()
	{
		$this->request = $_POST;
	}

	/**
	 * Callback de paiement depuis les données POST
	 * @return \HelloAsso\Callback\Payment
	 */
	public function getPayment()
	{
	    return new Payment();
	}
	
	/**
	 * Callback de campagne depuis les données POST
	 * @return \HelloAsso\Callback\Payment
	 */
	public function getCampaign()
	{
	    return new Campaign();
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

require_once __DIR__.'/callback/Payment.php';
require_once __DIR__.'/callback/Campaign.php';
