<?php
namespace HelloAsso\V5;

use HelloAsso\V5\Api\Authentication;
use HelloAsso\V5\Api\Request;

/**
 * Construction d'une requête d'API
 * 
 * @author fraoult
 * @license MIT
 */
class ResourceQuery extends Request
{

    const RESOURCE_PATH = null;

    /**
     * @var string
     */
    public static $defaultOrganisationSlug = null;


    /**
     * @var string
     */
    protected $organization_slug = null;

    /**
     * @var string
     */
    protected $resource_path = null;


    public static function setDefaultAuth(Authentication $authentication)
    {
        static::$authenticationInstance = $authentication;
    }

    /**
     * @param string $classname
     * @return static
     */
    public static function createFromResource($classname)
    {
        $query = new static();
        $query->setResourcePath($classname::RESOURCE_NAME);
        return $query;
    }

    public function __construct(Authentication $authentication = null)
    {
        parent::__construct($authentication);
        $this->organization_slug = static::$defaultOrganisationSlug;
        if (!empty(static::RESOURCE_PATH))
        {
            $this->setResourcePath(static::RESOURCE_PATH);
        }
    }

    /**
     * @param $organization_slug
     * @return $this
     */
    public function setOrganizationSlug($organization_slug)
    {
        $this->organization_slug = $organization_slug;
        return $this;
    }

    /**
     * @param string $resource_path
     * @return $this
     */
    public function setResourcePath($resource_path)
    {
        $this->resource_path = $resource_path;
        return $this;
    }

    /**
     * Override authorization
     *
     * @param string $auth
     * @return $this
     */
    public function setAuthorization($auth)
    {
        $this->addParam('Authorization', $auth);
        return $this;
    }

    /**
     * @return Api\Response
     * @throws Api\ResponseError
     */
    public function search()
    {
        $route = 'organizations/' . $this->organization_slug . '/'
            . $this->resource_path
        ;
        return $this->execute($route);
    }

    /**
     * @param string $id
     * @return Api\Response
     * @throws Api\ResponseError
     */
    public function get($id)
    {
        $route = $this->resource_path . '/' . $id;
        return $this->execute($route);
    }

}
