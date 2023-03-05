<?php
namespace HelloAsso\V5\Api;

use Exception;
use stdClass;

/**
 * API Exception
 * 
 * @author fraoult
 * @license MIT
 */
class ResponseError extends Exception
{

    /**
     * @var string
     */
    protected $body = null;
    /**
     * @var stdClass
     */
    protected $modelState = null;

    /**
     * @var Request
     */
    protected $request = null;

    public function __construct(
        $body = '',
        $code = 0,
        Request $request = null,
        Exception $previous = null
    )
    {
        $this->body    = $body;
        $this->request = $request;

        $curl = $request->getCurlData()['result'];
        $ctype = isset($curl['content_type']) ? $curl['content_type'] : null;

        $json = null;
        if (!empty($body) && $ctype == 'application/json')
        {
            $json = json_decode($body);
        }
        if ($json)
        {
            $message = property_exists($json, 'message')
                ? $json->message
                : 'unknown error'
            ;
        }
        else
        {
            $message = $body;
        }

        parent::__construct($message, $code, $previous);

        if (!empty($json))
        {
            if (property_exists($json, 'modelState'))
            {
                $this->modelState = $json->modelState;
            }
        }
    }

    public function dump()
    {
        return $this->request->dump();
    }
}