<?php
namespace HelloAsso\V5\Api;


/**
 * Construction d'une requête d'API
 * 
 * @author fraoult
 * @license MIT
 */
class Request
{
    /**
     * Base URL
     * @var string
     */
	const API_URL  = 'https://api.helloasso.com/v5/';

    /**
     * @var Authentication
     */
    public static $authenticationInstance = null;

    /**
     * @var Authentication
     */
    protected $authentication = null;

    /**
     * Query parameters
     * @var array
     */
    protected $params = [];

    /**
     * Data to send
     * @var array
     */
    protected $post_data = [];


    /**
     * Last cURL data
     * @var array
     */
    protected $curl_data = [
        'url' => null,
        'options' => [],
        'result' => []
    ];


    /**
     * In-line constructor
     * @param Authentication $authentication
     * @return static
     * @throws ResponseError
     */
    public static function create(Authentication $authentication = null)
    {
        return new static($authentication);
    }

    /**
     * Constructor
     * @param Authentication $authentication
     * @throws ResponseError
     */
    public function __construct(Authentication $authentication = null)
    {
        $auth = $authentication
            ?: static::$authenticationInstance
                ?: Authentication::init()
        ;

        $this->authentication = $auth;
    }

    /**
     * @param Authentication $authentication
     * @return $this
     */
    public function setAuthentication(Authentication $authentication)
    {
        $this->authentication = $authentication;
        return $this;
    }

    /**
     * @return Authentication
     */
    public function getAuthentication()
    {
        return $this->authentication;
    }

    /**
     * Set query parameters array
     * @param array $params
     * @return $this
     */
    public function setParams($params)
    {
        if (empty($params))
        {
            $this->params = [];
        }
        else
        {
            $this->params = array_filter($params);
        }
        return $this;
    }

    /**
	 * Add query parameter
	 * @param string $name
	 * @param string $value
	 * @return $this
	 */
	public function addParam($name, $value)
	{
		if ($value === null)
		{
			unset($this->params[$name]);
            return $this;
		}
		if ($value === false)
		{
			$value = 0;
		}
		if ($value === true)
		{
			$value = 1;
		}
		$this->params[$name] = $value;
		return $this;
	}

    /**
     * Add data value
     * @param string $field
     * @param string $value
     * @return $this
     */
    public function addPostData($field, $value)
    {
        if ($value === null)
        {
            unset($this->post_data[$field]);
            return $this;
        }
        if ($value === false)
        {
            $value = 0;
        }
        if ($value === true)
        {
            $value = 1;
        }
        $this->post_data[$field] = $value;
        return $this;
    }

	/**
	 * Build URL
	 * @return string
	 */
	protected function build($route)
	{
		$url = static::API_URL . $route;

        if (!empty($this->params))
		{
			$url .= '?' . http_build_query($this->params);
		}
		
		return $url;
	}
	
	/**
	 * Execute query
	 * @param string $route
	 * @return Response
     * @throws ResponseError
     */
	protected function execute($route)
	{
        if (!$this->authentication)
        {
            $this->authentication = Authentication::init();
        }
        if (!$this->authentication->isValid())
        {
            $this->authentication->refresh();
        }

        $headers = [
            'Authorization: ' . $this->authentication->getAuthHeader()
        ];
        $options = [
            CURLOPT_RETURNTRANSFER => 1
        ];
        if (!empty($this->post_data))
        {
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            $options[CURLOPT_POSTFIELDS] = http_build_query($this->post_data);
        }
        $options[CURLOPT_HTTPHEADER] = $headers;

        $url = $this->build($route);
        $curl = curl_init($url);
        curl_setopt_array($curl, $options);

		$body      = curl_exec($curl);
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curl_info = curl_getinfo($curl);

        $this->curl_data['url']     = $url;
        $this->curl_data['options'] = $options;
        $this->curl_data['result']  = $curl_info;

		curl_close($curl);
		return new Response($body, $http_code, $this);
	}

    // -------------------
    // Tools
    // -------------------

    /**
     * Get last request info
     * @return array
     */
    public function getCurlData()
    {
        return $this->curl_data;
    }

    /**
     * Dump infos about request
     *
     * @return array [
     *  'url' => string,
     *  'options' => array
     *  'auth' => [
     *      'access'  => array,
     *      'refresh' => array
     *  ]
     * ]
     */
    public function dump()
    {
        $access = (array)$this->authentication->getAccessToken();
        $refresh = (array)$this->authentication->getRefreshToken();
        foreach($access as $i => $v)
        {
            if (substr($i, 0, 3) == "\0*\0")
            {
                $access[substr($i, 3)] = $v;
                unset($access[$i]);
            }
        }

        foreach($refresh as $i => $v)
        {
            if (substr($i, 0, 3) == "\0*\0")
            {
                $refresh[substr($i, 3)] = $v;
                unset($refresh[$i]);
            }
        }

        return [
            'url'     => $this->curl_data['url'],
            'options' => $this->curl_data['options'],
            'auth'    => [
                'access'  => $access,
                'refresh' => $refresh
            ]
        ];
    }
}
