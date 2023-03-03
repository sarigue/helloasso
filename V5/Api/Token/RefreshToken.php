<?php

namespace HelloAsso\V5\Api\Token;

class RefreshToken
{

    /**
     * @var string
     */
    protected $value = null;

    /**
     * @var int
     */
    protected $expires_in = 0;

    /**
     * @var int
     */
    protected $expires_at = 0;

    /**
     * @param string $value
     * @param int $expires_in
     */
    public function __construct($value, $expires_in)
    {
        $this->value = $value;
        $this->expires_in = (int)$expires_in;
        $this->expires_at = time() + $this->expires_in;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }

    /**
     * Check if token is expired
     * @return bool
     */
    public function isExpired()
    {
        return $this->expires_at <= time();
    }

}