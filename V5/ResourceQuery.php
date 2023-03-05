<?php
namespace HelloAsso\V5;

use HelloAsso\V5\Api\Authentication;
use HelloAsso\V5\Api\Request;
use HelloAsso\V5\Api\Response;
use HelloAsso\V5\Api\ResponseError;

/**
 * Build API Request
 * 
 * @author fraoult
 * @license MIT
 */
class ResourceQuery extends Request
{
    const RESOURCE_CLASS = null;

    /**
     * @var string
     */
    public static $defaultOrganisationSlug = null;

    /**
     * @var string
     */
    protected $resource_path = null;

    /**
     * @var string
     */
    protected $resource_class = null;


    /**
     * @var string
     */
    protected $organization_slug = null;

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
        $query->setResourceClass($classname);
        return $query;
    }

    public function __construct(Authentication $authentication = null)
    {
        parent::__construct($authentication);
        $this->organization_slug = static::$defaultOrganisationSlug;

        $class = static::RESOURCE_CLASS; /** @var Resource $class */
        if (!empty($class))
        {
            $this->setResourceClass($class);
        }
        if(!empty($class) && !empty($class::RESOURCE_NAME))
        {
            $this->setResourcePath($class::RESOURCE_NAME);
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
     * @param string $resource_class
     * @return $this
     */
    public function setResourceClass($resource_class)
    {
        $this->resource_class = $resource_class;
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
     * @return Response
     * @throws ResponseError
     */
    public function search()
    {
        $route = 'organizations/' . $this->organization_slug . '/'
            . $this->resource_path
        ;
        return $this->execute($route)->setResourceClass($this->resource_class);
    }

    /**
     * @param string $id
     * @return Response
     * @throws ResponseError
     */
    public function get($id)
    {
        $route = $this->resource_path . '/' . $id;
        return $this->execute($route)->setResourceClass($this->resource_class);
    }

}
