<?php 
namespace HelloAsso\V3;

use HelloAsso\V3\Traits\Testable;
use stdClass;


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
	 * @param stdClass|array $json
	 */
	public function __construct($json)
	{
		foreach($json as $field => $value)
		{
			$this->$field = $value;
		}
	}
}



