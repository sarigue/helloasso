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
    
    public function __construct($json, \Exception $previous = NULL)
    {
        parent::__construct($json->message, 500, $previous);
        $this->api_code = $json->code;
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
        return property_exists($json, 'code') && property_exists($json, 'message');
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