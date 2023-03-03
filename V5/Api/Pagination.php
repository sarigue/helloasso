<?php
namespace HelloAsso\V5\Api;


/**
 * Classe de pagination
 * 
 * @author fraoult
 * @license MIT
 */
class Pagination
{
    /** @var int */ public $page;
    /** @var int */ public $max_page;
    /** @var int */ public $result_per_page;

    public function __construct($json)
    {
        foreach($json as $field => $value)
        {
            $this->$field = $value;
        }
    }
}

