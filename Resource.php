<?php 
namespace HelloAsso;

require_once __DIR__.'/api/Query.php';
require_once __DIR__.'/api/Response.php';
require_once __DIR__.'/api/Pagination.php';
require_once __DIR__.'/api/Exception.php';

require_once __DIR__ . '/traits/ModelGetter.php';
require_once __DIR__ . '/traits/Testable.php';

/**
 * Classe de base des objets HelloAsso
 * Offre le constructeur depuis le json
 * 
 * @author fraoult
 * @license MIT
 */
abstract class Resource
{
    use Testable;
    
	/**
	 * @param \stdClass|array $json
	 */
	public function __construct($json)
	{
		foreach($json as $field => $value)
		{
			$this->$field = $value;
		}
	}
}

require_once __DIR__ . '/resource/Action.php';
require_once __DIR__ . '/resource/BasicCampaign.php';
require_once __DIR__ . '/resource/Campaign.php';
require_once __DIR__ . '/resource/Organism.php';
require_once __DIR__ . '/resource/Payment.php';

