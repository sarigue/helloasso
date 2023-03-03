<?php
namespace HelloAsso\V3\Api;

require_once __DIR__.'/Pagination.php';
require_once __DIR__.'/Exception.php';

/**
 * Réponse de l'API HelloAsso
 * 
 * @author fraoult
 * @license MIT
 */
class Response
{
	/** @var string     */ protected $class;
	/** @var boolean    */ protected $is_collection;
    /** @var mixed      */ protected $data;
    /** @var integer    */ protected $code;
    /** @var Pagination */ protected $pagination;
    /** @var Exception  */ protected $exception;
    
    /**
     *
     * @param \stdClass $json
     * @param integer   $code
     */
    public function __construct($json, $code = NULL)
    {
        $this->code = $code;
        
        if (Exception::isError($json))
        {
            $this->exception = new Exception($json);
            return;
        }
        if (property_exists($json, 'resources'))
        {
            $this->data = $json->resources;
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
     * @param string $classname
     * @return \HelloAsso\Api\Response
     */
    public function setResourceClass($classname)
    {
    	$this->class = $classname;
    	return $this;
    }
    
    /**
     * @return boolean
     */
    public function isError()
    {
        return !empty($this->exception);
    }
    
    /**
     * @return \HelloAsso\Api\Exception
     */
    public function getException()
    {
        return $this->exception;
    }
    
    /**
     * @throws \HelloAsso\Api\Exception
     * @return $this Si l'exception n'est pas lancée
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
     * @return number
     */
    public function getHttpCode()
    {
        return $this->code;
    }
    
    /**
     * @return \stdClass|\stdClass[]
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * @return boolean
     */
    public function isCollection()
    {
        return $this->is_collection;
    }
    
    /**
     * @return \HelloAsso\Api\Pagination
     */
    public function getPagination()
    {
        return $this->pagination;
    }
    
    /**
     * Parse la réponse en tant que $classe
     * et retourne la ressource ou collection de ressources
     * @param string $class
     * @return \HelloAsso\V3\Resource[]|\HelloAsso\V3\Resource
     */
    public function getResource($class = NULL)
    {
    	if (empty($class) && empty($this->class))
    	{
    		throw new \Exception('Aucune classe définie pour '.__METHOD__);
    	}
    	
    	if (empty($class))
    	{
    		$class = $this->class;
    	}
    	
    	if ($this->is_collection)
        {
            $collection = [];
            foreach($this->data as $data)
            {
                $collection[] = new $class($data);
            }
            return $collection;
        }
        
        return new $class($this->data);
    }
}
