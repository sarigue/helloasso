<?php
namespace HelloAsso\V5\Api;


use stdClass;

/**
 * API Response
 * 
 * @author fraoult
 * @license MIT
 */
class Response
{
    /** @var int        */ protected $code;
    /** @var string     */ protected $body;
    /** @var Request    */ protected $request;

    /** @var boolean    */ protected $is_collection;
    /** @var mixed      */ protected $data;
    /** @var Pagination */ protected $pagination;

    /** @var string         */ protected $class;
    /** @var ResponseError  */ protected $exception;

    /**
     *
     * @param string $body
     * @param int    $code
     */
    public function __construct($body, $code = NULL, Request $request = null)
    {
        $this->body    = $body;
        $this->code    = $code;
        $this->request = $request;

        if ($code > 299)
        {
            $this->exception = new ResponseError($body, $code, $request);
            return;
        }

        $json = json_decode($body, false);
        if (
            property_exists($json, 'resources')
            && is_array($json->resources)
        )
        {
            $this->data = $json->resources;
            $this->is_collection = true;
        }
        elseif (
            property_exists($json, 'data')
            && is_array($json->data)
        )
        {
            $this->data = $json->data;
            $this->is_collection = true;
        }
        else
        {
            $this->data = $json;
            $this->is_collection = false;
        }

        if (property_exists($json, 'pagination'))
        {
            $this->pagination = new Pagination($json->pagination);
        }
    }

    /**
     * Set Resource Class
     * @param string $classname
     * @return $this
     */
    public function setResourceClass($classname)
    {
    	$this->class = $classname;
    	return $this;
    }
    
    /**
     * Check if response is an error
     * @return boolean
     */
    public function isError()
    {
        return !empty($this->exception);
    }
    
    /**
     * Get error
     * @return ResponseError
     */
    public function getException()
    {
        return $this->exception;
    }
    
    /**
     * Throw Exception or return $this
     * @return $this
     * @throws ResponseError
     */
    public function throwException()
    {
        if (!empty($this->exception))
        {
            throw $this->exception;
        }
        return $this;
    }
    
    /**
     * Get Http response code
     * @return int
     */
    public function getHttpCode()
    {
        return $this->code;
    }
    
    /**
     * Get formated data
     * @return stdClass|stdClass[]
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * Check if response is a collection of resources
     * @return bool
     */
    public function isCollection()
    {
        return $this->is_collection;
    }
    
    /**
     * Get pagination
     * @return Pagination
     */
    public function getPagination()
    {
        return $this->pagination;
    }


    /**
     * Parse response as $class Resource
     * @param $class
     * @return Resource
     * @throws \Exception
     */
    public function getResource($class = null)
    {

        if ($this->is_collection)
        {
            return null;
        }

        if ($this->isError())
        {
            return null;
        }

        $class = $this->getResourceClass($class);
        return new $class($this->data);
    }

    /**
     * Parse response as $class Resource list
     * @param string $class
     * @return Resource[]
     * @throws \Exception
     */
    public function getCollection($class = null)
    {
        if (!$this->is_collection)
        {
            return null;
        }

        if ($this->isError())
        {
            return null;
        }

        $class = $this->getResourceClass($class);
        $collection = [];
        foreach($this->data as $data)
        {
            $collection[] = new $class($data);
        }
        return $collection;
    }


    /**
     * Parse response as $class Resource or list
     * @param string $class
     * @return Resource[]|Resource
     * @throws \Exception
     */
    public function getContent($class = NULL)
    {
        if ($this->is_collection)
        {
            return $this->getCollection($class);
        }

        return $this->getResource($class);
    }


    /**
     * Get Ressource class name
     * @param string $class
     * @return string
     * @throws \Exception
     */
    protected function getResourceClass($class = null)
    {
        if (empty($class) && empty($this->class))
        {
            throw new \Exception('No class defined');
        }
        return $class ?: $this->class;
    }


    // ------------
    // Tools
    // ------------

    /**
     * Get request
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Dump response data
     *
     * @return array [
     *  'code'  => int,
     *  'body'  => string
     *  'infos' => curl result
     * ]
     */
    public function dump()
    {
        return [
            'code'  => $this->code,
            'body'  => $this->body,
            'infos' => $this->request->getCurlData()['result']
        ];
    }

}
