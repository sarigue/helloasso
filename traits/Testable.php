<?php

namespace HelloAsso;

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
    protected static $TEST_MODE = FALSE;
    
    /**
     * Utiliser le mode test
     * @param boolean $test_mode
     */
    public static function setTestMode($test_mode)
    {
        self::$TEST_MODE = (boolean)$test_mode;
    }
    
    public function __set($name, $value)
    {
        if (! self::$TEST_MODE)
        {
            throw new \Exception('Cannot access protected or private property '.get_class($this).'::'.$name);
        }
        if (! property_exists($this, $name))
        {
            throw new \Exception('Inexistant property '.get_class($this).'::'.$name);
        }
        $this->$name = $value;
    }
    
    public function __get($name)
    {
        if (! self::$TEST_MODE)
        {
            throw new \Exception('Cannot access protected or private property '.get_class($this).'::'.$name);
        }
        return $this->$name;
    }
}