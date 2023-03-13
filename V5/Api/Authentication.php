<?php

namespace HelloAsso\V5\Api;

use Exception;
use HelloAsso\V5\Api\Token\AccessToken;
use HelloAsso\V5\Api\Token\RefreshToken;

class Authentication
{

    /**
     * URL for authentication
     */
    const URL = 'https://api.helloasso.com/oauth2/token';

    /**
     * Default client ID
     * @var string
     */
    public static $defaultClientId = null;

    /**
     * Default client Secret
     * @var string
     */
    public static $defaultClientSecret = null;

    /**
     * Bearer access token
     * @var AccessToken
     */
    protected $access_token = null;

    /**
     * Refresh token
     * @var RefreshToken
     */
    protected $refresh_token = null;


    /**
     * Client ID
     * @var string
     */
    protected $client_id = null;

    /**
     * Client Secret
     * @var string
     */
    protected $client_secret = null;

    /**
     * @param string $client_id
     * @param string $client_secret
     * @return static
     * @throws ResponseError
     */
    public static function init($client_id = null, $client_secret = null)
    {
        $auth = new static($client_id, $client_secret);
        $auth->authenticate();
        return $auth;
    }

    public function __construct($client_id = null, $client_secret = null)
    {
        $this->client_id = $client_id ?: static::$defaultClientId;
        $this->client_secret = $client_secret ?: static::$defaultClientSecret;
    }

    /**
     * Return "Bearer xxxx"
     * @return string
     */
    public function getAuthHeader()
    {
        if (!$this->access_token)
        {
            return null;
        }
        return ucfirst($this->access_token->getType())
            . ' '
            . $this->access_token->getValue()
            ;
    }

    /**
     * @return AccessToken
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * @return RefreshToken
     */
    public function getRefreshToken()
    {
        return $this->refresh_token;
    }

    /**
     * Return true if access token exists
     * @return bool
     */
    public function isAuthenticate()
    {
        return !empty($this->access_token);
    }

    /**
     * Return true if access token is valid
     * @return bool
     */
    public function isValid()
    {
        if (!$this->isAuthenticate())
        {
            return false;
        }
        return !$this->access_token->isExpired();
    }

    /**
     * Return true if refresh token is expired
     * @return bool
     */
    public function isRefreshExpired()
    {
        return $this->refresh_token->isExpired();
    }

    /**
     * Call authentication API
     * @return $this
     * @throws ResponseError
     */
    public function authenticate()
    {
        if (!$this->client_id)
        {
            throw new ResponseError('No client ID');
        }
        if (!$this->client_secret)
        {
            throw new ResponseError('No client Secret');
        }

        $data = [
            'client_id'     => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type'    => 'client_credentials'
        ];
        $result = $this->exec($data);
        $this->setTokens($result);
        return $this;
    }


    /**
     * Call refresh API
     * @return $this
     * @throws ResponseError
     */
    public function refresh()
    {
        if (!$this->client_id)
        {
            throw new ResponseError('No client ID');
        }
        if (!$this->refresh_token)
        {
            return $this->authenticate();
        }
        if ($this->isRefreshExpired())
        {
            return $this->authenticate();
        }
        $data = [
            'client_id' => $this->client_id,
            'refresh_token' => $this->refresh_token,
            'grant_type' => 'refresh_token'
        ];
        $result = $this->exec($data);
        $this->setTokens($result);
        return $this;
    }

    /**
     * Set Token objects
     * @param $result
     * @return void
     */
    protected function setTokens($result)
    {
        $access  = $result['access_token'];
        $type    = $result['token_type'];
        $refresh = $result['refresh_token'];
        $expire  = $result['expires_in'];

        $this->access_token = new AccessToken($access, $type);
        $this->refresh_token = new RefreshToken($refresh, $expire);
    }

    /**
     * Exec cURL
     * @param array $data
     * @return array
     * @throws ResponseError
     * @throws Exception
     */
    protected function exec(array $data)
    {
        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
        ];
        $options = [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSLVERSION => 7 // Force requests to use TLS 1.2
        ];

        $curl = curl_init(static::URL);
        curl_setopt_array($curl, $options);
        $body      = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $infos     = curl_getinfo($curl);
        curl_close($curl);

        if ($http_code !== 200)
        {
            throw new AuthError($body, $options, $infos);
        }

        return json_decode($body, true);
    }

}
