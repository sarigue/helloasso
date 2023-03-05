<?php

namespace HelloAsso\V5;

use HelloAsso\V5\Api\Authentication;
use HelloAsso\V5\Api\Request;
use HelloAsso\V5\Api\ResponseError;

/**
 * Main entry point
 *
 * @author sarigue - https://github.com/sarigue
 * @license MIT
 */
class HelloAsso
{

    public static function initialize()
    {
        return new static();
    }

    public function setClient($id, $secret)
    {
        static::client($id, $secret);
        return $this;
    }

    public function setOrganization($slug)
    {
        static::organization($slug);
        return $this;
    }

    /**
     * @throws ResponseError
     */
    public function setAuth(Authentication $auth)
    {
        static::initAuthentication($auth);
        return $this;
    }

    /**
     * @throws ResponseError
     */
    public function authenticate()
    {
        static::initAuthentication();
        return $this;
    }

    // -----------------------
    // ------ Static methods
    // -----------------------

    /**
     * Set default ID and password
     * @param string $id
     * @param string $secret
     */
    public static function client($id, $secret)
    {
        Authentication::$defaultClientId = $id;
        Authentication::$defaultClientSecret = $secret;
    }

    /**
     * Set default organization ID and slug
     * @param string $slug
     */
    public static function organization($slug)
    {
        ResourceQuery::$defaultOrganisationSlug = $slug;
    }

    /**
     * Set with Authentication or init new Authentication
     * @param Authentication $auth
     * @throws ResponseError
     */
    public static function initAuthentication($auth = null)
    {
        Request::$authenticationInstance = $auth ?: Authentication::init();
    }


}


