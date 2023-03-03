<?php
namespace HelloAsso\V3\Api;

require_once __DIR__.'/Response.php';

/**
 * Construction d'une requête d'API
 * 
 * @author fraoult
 * @license MIT
 */
class Query
{
    /**
     * URL de base de l'API HelloAsso
     * @var string
     */
	const API_URL  = 'https://api.helloasso.com/v3/';
	
	/**
	 * ID de connexion à l'API
	 * @var string
	 */
	static public $API_ID   = NULL;
	/**
	 * Mot de passe de connexion à l'API
	 * @var string
	 */
	static public $API_PASS = NULL;
	
	/**
	 * ID par défaut de l'organisme
	 * @var string
	 */
	static public $ORGANISM_ID   = NULL;
	/**
	 * ID par défaut de la campagne
	 * @var string
	 */
	static public $CAMPAIGN_ID   = NULL;
	/**
	 * Slug par défaut de l'organisme
	 * @var string
	 */
	static public $ORGANISM_SLUG = NULL;
	
	protected $page             = NULL;
	protected $results_per_page = NULL;
	protected $query            = [];
	protected $resource         = NULL;
	protected $class            = NULL;
	protected $id               = NULL;
	protected $organism_slug    = FALSE;
	protected $organism_id      = NULL;
	protected $campaign_id      = NULL;
	protected $public           = FALSE;
	
	/**
	 * Création
	 * @param string $resource_name
	 * @param string $id
	 * @return \HelloAsso\Api\Query
	 */
	public static function create($resource_name, $id = NULL)
	{
	    return new static($resource_name, $id);
	}
	
	/**
	 * Elements d'authentification par défaut
	 * @param string $api_id
	 * @param string $api_pass
	 */
	public static function setDefaultAuth($api_id, $api_pass)
	{
	    static::$API_ID   = $api_id;
	    static::$API_PASS = $api_pass;
	}
	
	/**
	 * Organisme Id utilisé pour toutes les requêtes
	 * @param string $organism_id
	 */
	public static function setDefaultOrganismId($organism_id)
	{
	    static::$ORGANISM_ID = $organism_id;
	}

	/**
	 * Organisme Slug utilisé pour les requêtes publiques
	 * @param string $organism_slug
	 */
	public static function setDefaultOrganismSlug($organism_slug)
	{
	    static::$ORGANISM_SLUG = $organism_slug;
	}
	
	/**
	 * Le campaign_id utilisé pour toutes les requêtes
	 * @param string $campaign_id
	 */
	public static function setDefaultCampaingId($campaign_id)
	{
	    static::$CAMPAIGN_ID = $campaign_id;
	}
	
	/**
	 * Constructeur
	 * @param string $resource
	 * @param string $id
	 */
	public function __construct($resource_name, $id = NULL)
	{
	    // Si la ressource données est en fait une classe de ressource
	    if (strpos($resource_name, 'HelloAsso\\V3\\Resource\\') !== FALSE && class_exists($resource_name, false))
	    {
	        $class = $resource_name;
	        if (defined($class.'::RESOURCE_NAME'))
	        {
	            $resource_name = $class::RESOURCE_NAME;
	            $this->class   = $class;
	        }
	        else
	        {
	            throw new \Exception('Pas de constante RESOURCE_NAME pour la classe '.$class);
	        }
	    }
	    $this->resource = $resource_name;
		$this->setOrganismId(self::$ORGANISM_ID);
		$this->setCampaignId(self::$CAMPAIGN_ID);
		$this->setOrganismSlug(self::$ORGANISM_SLUG);
		$this->setId($id);
	}
	
	/**
	 * 
	 * @param string $id
	 * @return $this
	 */
	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}
	
	/**
	 * Numéro de page à chercher
	 * @param integer $page
	 * @return $this
	 */
	public function setPage($page)
	{
		$this->page = $page;
		return $this;
	}
	
	/**
	 * Nombre de résultat par page
	 * @param integer $results_per_page
	 * @return $this
	 */
	public function setResultsPerPage($results_per_page)
	{
		$this->results_per_page = $results_per_page;
		return $this;
	}
	
	/**
	 * Organisme
	 * @param string $organism_id
	 * @return $this
	 */
	public function setOrganismId($organism_id)
	{
		$this->organism_id = $organism_id;
		return $this;
	}

	/**
	 * Organisme
	 * @param string $slug
	 * @return \HelloAsso\Api\Query
	 */
	public function setOrganismSlug($slug)
	{
	    $this->organism_slug = $slug;
	    return $this;
	}
	
	/**
	 * Campagne
	 * @param string $campaign_id
	 * @return $this
	 */
	public function setCampaignId($campaign_id)
	{
		$this->campaign_id = $campaign_id;
		return $this;
	}

    /**
     * Utiliser l'API public
     * @param boolean $public
     * @return \HelloAsso\Api\Query
     */
	public function setPublic($public = TRUE)
	{
	    $this->public = (boolean)$public;
	    return $this;
	}
	
	/**
	 * Utiliser l'API privé
	 * @param boolean $public
	 * @return \HelloAsso\Api\Query
	 */
	public function setPrivate($private = TRUE)
	{
	    return $this->setPublic(! $private);
	}
	
	/**
	 * Ajouter un paramètre libre
	 * @param string $name
	 * @param string $value
	 * @return $this
	 */
	public function addParam($name, $value)
	{
		if ($value === NULL)
		{
			unset($this->query[$name]);
		}
		if ($value === false)
		{
			$value = 0;
		}
		if ($value === true)
		{
			$value = 1;
		}
		$this->query[$name] = $value;
		return $this;
	}
	
	/**
	 * Construction de l'URL de requête
	 * @return string
	 */
	public function build()
	{
		$url = self::API_URL;
		
		// Appel API Public
		
		if ($this->public)
		{
		    $url .= '/public/organizations/';
		    if ($this->organism_id)
		    {
		        $this->addParam('organism_id', $this->organism_id);
		    }
		    elseif ($this->organism_slug)
		    {
		        $url .= $this->organism_slug.'/';
		    }
		    else
		    {
		        throw new \Exception('L\'appel à l\'API publique doit avoir un slug ou ID d\'organisme');
		    }
		}
		else // API Privé
		{
		    if ($this->organism_id)
		    {
		        $url .= '/organizations/'.$this->organism_id.'/';
		    }
		    
		    if ($this->campaign_id)
		    {
		        $url .= '/campaigns/'.$this->campaign_id.'/';
		    }
		}
		
		$url .= $this->resource;
		
		if ($this->id)
		{
			$url .= '/'.$this->id;
		}
		
		$url .= '.json';
		
		if (!empty($this->query))
		{
			$url .= '?' . http_build_query($this->query);
		}
		
		return $url;
	}
	
	/**
	 * Exécute la requête
	 * @param boolean $public
	 * @return Response
	 */
	public function execute()
	{
		$curl = curl_init($this->build());
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		
		if (! $this->public)
		{
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($curl, CURLOPT_USERPWD, static::$API_ID . ':' . static::$API_PASS);
		}
		
		$json      = json_decode(curl_exec($curl));
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);
		return (new Response($json, $http_code))->setResourceClass($this->class);
	}
}
