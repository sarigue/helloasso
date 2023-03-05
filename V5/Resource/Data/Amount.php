<?php

namespace HelloAsso\V5\Resource\Data;

use HelloAsso\V5\Resource;

class Amount extends Resource
{

    /** @var float */ public $total;
    /** @var float */ public $vat;
    /** @var int   */ public $discount;

    public function __construct($json)
    {
        parent::__construct($json);
        $this->total = $this->total / 100;
        $this->vat   = $this->vat   / 100;
    }

}