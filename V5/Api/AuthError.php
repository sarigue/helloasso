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
class AuthError extends Exception
{

    /**
     * @var string
     */
    protected $body = null;
    /**
     * @var array
     */
    protected $options = null;

    /**
     * @var array
     */
    protected $result = null;


    public function __construct(
        $body,
        array $options,
        array $infos,
        Exception $previous = null
    )
    {
        $this->options = $options;
        $this->body    = $body;
        $this->result  = $infos;

        parent::__construct(
            'Erreur d\'authentification',
            $infos['http_code'],
            $previous
        );

    }

    public function dump()
    {
        return [
            'url'     => $this->result['url'],
            'options' => $this->options,
            'result'  => $this->result,
            'body'    => $this->body
        ];
    }
}