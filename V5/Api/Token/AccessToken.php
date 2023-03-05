<?php

namespace HelloAsso\V5\Api\Token;

class AccessToken
{
    /**
     * Right of access to public data
     * @var string
     */
    const RIGHT_ACCESS_PUBLIC_DATA = 'AccessPublicData';

    /**
     * Right of access to transactions
     * @var string
     */
    const RIGHT_ACCESS_TRANSACTIONS = 'AccessTransactions';

    /**
     * Right to use checkout
     * @var string
     */
    const RIGHT_CHECKOUT = 'Checkout';

    const ADMIN_ORGANIZATION = 'OrganizationAdmin';
    const ADMIN_FORM         = 'FormAdmin';
    const ADMIN_GROUP        = 'GroupAdmin';


    /**
     * Raw value
     * @var string
     */
    protected $value = null;

    /**
     * Token type
     * @var string
     */
    protected $type = 'bearer';

    /**
     * @var string
     */
    protected $jti;

    /**
     * @var array
     */
    protected $cps = [];

    /**
     * @var string
     */
    protected $urs;

    /**
     * @var int
     */
    protected $nbf;

    /**
     * Expiration date
     * @var int
     */
    protected $exp;

    /**
     * @var string
     */
    protected $aud;


    public function __construct($token, $type = 'bearer')
    {
        $this->value = $token;
        $this->type  = $type;
        list($header, $payload, $sign) = explode('.', $token);
        $payload = json_decode(base64_decode($payload), true);
        $this->jti = $payload['jti'];
        $this->cps = $payload['cps'];
        $this->urs = $payload['urs'];
        $this->nbf = (int)$payload['nbf'];
        $this->exp = (int)$payload['exp'];
        $this->iss = $payload['iss'];
        $this->aud = $payload['aud'];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getValue();
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Check if token is expired
     * @return bool
     */
    public function isExpired()
    {
        return $this->exp <= time();
    }

    /**
     * Check right
     * @param string $right - see RIGHT_* class constants
     * @return bool
     */
    public function hasRight($right)
    {
        return in_array($this->cps, $right);
    }

    /**
     * Check if token is organization administrator
     * @return bool
     */
    public function isOrganizationAdmin()
    {
        return $this->urs == static::ADMIN_ORGANIZATION;
    }

    public function isFormAdmin()
    {
        return $this->urs == static::ADMIN_FORM;
    }

    public function isGroupAdmin()
    {
        return $this->urs == static::ADMIN_GROUP;
    }

}