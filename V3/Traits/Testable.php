<?php

namespace HelloAsso\V3\Traits;

use Exception;

/**
 * Méthode liées au teste :
 * Mode test
 * Pouvoir surcharger les propriétées protégées
 * 
 * @author fraoult
 * @license MIT
 */
trait Testable
{
	/**
	 * Flag du mode test
	 * @var string
	 */
    protected static $TEST_MODE  = FALSE;
    
    /**
     * FALSE pour ne permettre de ne définir que les propriété existantes.
     * TRUE pour indiquer que l'obet peut se voir ajouter
     * de nouvelles propriété à la volée (conseillé pour gérer sans erreur
     * d'éventuelles nouvelles données transmises par HelloAsso)
     * 
     * @var boolean
     */
    public    static $EXTENDABLE = TRUE;
    
    /**
     * Utiliser le mode test
     * @param boolean $test_mode
     */
    public static function setTestMode($test_mode)
    {
        self::$TEST_MODE = (boolean)$test_mode;
    }
    
    /**
     * Magic function __set
     * Autorise la modification d'une propriété inacessible si "Mode Test"
     * Autorise l'ajout d'une nouvelle propriété si "Extensible" (conseillé pour gérer d'éventuelles nouvelles données dans l'API HelloAsso)
     * @param string $name
     * @param mixed $value
     * @throws Exception
     */
    public function __set($name, $value)
    {
    	$property_exists = property_exists($this, $name);
    	
    	// La propriété existe mais on est dans  __set :
    	// c'est que la propriété n'est pas accessible : Erreur
    	
    	if (! self::$TEST_MODE && $property_exists)
        {
            throw new Exception('Cannot access protected or private property '.get_class($this).'::'.$name);
        }
        
        // La propriété n'existe pas et on n'est pas "extensible" : Erreur
        
        if (! $property_exists && ! self::$EXTENDABLE)
        {
        	throw new Exception('Inexistant property '.get_class($this).'::'.$name);
        }
        
        // Set la propriété
        
        $this->$name = $value;
    }
    
    /**
     * Refus de l'accès la la propriété si pas "Mode Test"
     * @param string $name
     * @throws Exception
     * @return mixed
     */
    public function __get($name)
    {
        if (! self::$TEST_MODE)
        {
            throw new Exception('Cannot access protected, private or inexistant property '.get_class($this).'::'.$name);
        }
        
        return $this->$name;
    }
}