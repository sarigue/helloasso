<?php
namespace HelloAsso\Api;

/**
 * Exception de l'API
 * 
 * @author fraoult
 * @license MIT
 */
class Exception extends \Exception
{
    /** @var string */ protected $api_code;
	/** @var string */ protected $api_className;
	
    public function __construct($json, \Exception $previous = NULL)
    {
    	$message = NULL;
    	if (!empty($json) && property_exists($json, 'message'))
    	{
    		$message = $json->message;
    	}
    	if (empty($message) && !empty($json) && property_exists($json, 'Message'))
    	{
    		$message = $json->Message;
    	}
    	if (empty($message))
    	{
    		$message = 'Unknown error';
    	}
    	$this->api_code      = !empty($json) && property_exists($json, 'code') ? $json->code : NULL;
    	$this->api_className = !empty($json) && property_exists($json, 'ClassName') ? $json->ClassName : NULL;
    	parent::__construct($message, 500, $previous);
    }
    
    /**
     * @return string
     *          code API
     */
    public function getApiCode()
    {
        return $this->api_code;
    }
    
    /**
     * @param \stdClass $json
     *          réponse json décodée sous forme d'objet
     * @return boolean
     *          true si la réponse est une erreur
     */
    public static function isError($json)
    {
    	if (empty($json))
    	{
    		return TRUE;
    	}
        return property_exists($json, 'code') || property_exists($json, 'ClassName');
    }
    
    /**
     * Lancer l'erreur si la réponse est une erreur
     * @param \stdClass $json
     *          réponse json décodée sous forme d'objet
     * @throws self
     */
    public static function throwIfError($json)
    {
        if (self::isError($json))
        {
            error_log($json->code . ': ' . $json->message);
            throw new self($json);
        }
    }
}