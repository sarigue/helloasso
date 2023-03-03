<?php
namespace HelloAsso\V3\Api;


/**
 * Classe de pagination
 * 
 * @author fraoult
 * @license MIT
 */
class Pagination
{
    /** @var integer */ public $page;
    /** @var integer */ public $max_page;
    /** @var integer */ public $result_per_page;

    public function __construct($json)
    {
        foreach($json as $field => $value)
        {
            $this->$field = $value;
        }
    }
}

