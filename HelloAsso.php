<?php

require_once __DIR__.'/traits/ModelGetter.php';
require_once __DIR__.'/traits/Testable.php';

require_once __DIR__.'/Resource.php';
require_once __DIR__.'/Callback.php';

require_once __DIR__.'/api/Query.php';
require_once __DIR__.'/api/Response.php';
require_once __DIR__.'/api/Pagination.php';
require_once __DIR__.'/api/Exception.php';

use HelloAsso\Api\Query;
use HelloAsso\Resource;
use HelloAsso\Callback;

/**
 * Point d'entrée de la bibliothèque HelloAsso
 * 
 * @author fraoult
 * @license MIT
 */
class HelloAsso
{
    /**
     * Configurer l'ID de l'api et le mot de passe
     * @param string $id
     * @param string $password
     */
	public static function apiConfig($id, $password)
	{
		Query::setDefaultAuth($id, $password);
	}
	
	/**
	 * Mode test : autorise de réécrire une propriété protégée
	 * pour lui forcer une valeur
	 * @param string $test_mode
	 */
	public static function setTestMode($test_mode)
	{
		Resource::setTestMode($test_mode);
		Callback::setTestMode($test_mode);
	}	
}


