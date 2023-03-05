<?php

namespace HelloAsso\V3;

use HelloAsso\Callback\Campaign;
use HelloAsso\Callback\Payment;
use HelloAsso\V3\traits\Testable;


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
	public static function createPayment()
	{
	    return new Payment();
	}
	
	/**
	 * Callback de campagne depuis les données POST
	 * @return \HelloAsso\Callback\Payment
	 */
	public static function createCampaign()
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