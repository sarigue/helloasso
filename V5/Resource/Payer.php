<?php

namespace HelloAsso\V5\Resource;


use HelloAsso\V5\Resource;
use HelloAsso\V5\Traits\Response\Meta;

/**
 * Paiement HelloAsso
 * 
 * @author fraoult
 * @license MIT
 */
class Payer extends Resource
{
    use Meta;

    /** @var string    */ public $email;
    /** @var string    */ public $address;
    /** @var string    */ public $city;
    /** @var string    */ public $zipCode;
    /** @var string    */ public $country;
    /** @var \DateTime */ public $dateOfBirth;
    /** @var bool      */ public $firstName;
    /** @var bool      */ public $lastName;

    public function __construct($json)
    {
        parent::__construct($json);
        $this->dateOfBirth = new \DateTime($this->dateOfBirth);
    }
}
