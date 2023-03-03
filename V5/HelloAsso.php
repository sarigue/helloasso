<?php

namespace HelloAsso\V5;

use HelloAsso\V5\Api\Authentication;
use HelloAsso\V5\Api\Request;
use HelloAsso\V5\Callback;
use HelloAsso\V5\Resource;

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

    public function setOrganization($slug, $id = null)
    {
        static::organization($slug, $id);
        return $this;
    }

    public function setAuth(Authentication $auth)
    {
        static::initAuthentication($auth);
        return $this;
    }

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
     * @param string $password
     */
    public static function client($id, $secret)
    {
        Authentication::$defaultClientId = $id;
        Authentication::$defaultClientSecret = $secret;
    }

    /**
     * Set default organization ID and slug
     * @param string $slug
     * @param string $id
     */
    public static function organization($slug, $id = null)
    {
        ResourceQuery::$defaultOrganisationSlug = $slug;
    }

    /**
     * Set with Authentication or init new Authentication
     * @param Authentication $auth
     * @throws Api\ResponseError
     */
    public static function initAuthentication($auth = null)
    {
        Request::$authenticationInstance = $auth ?: Authentication::init();
    }


}


