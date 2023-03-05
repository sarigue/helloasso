<?php

namespace HelloAsso\V5\Resource\Data;

use HelloAsso\V5\Resource;

class Banner extends Resource
{

    /** @var string */ public $fileName;
    /** @var string */ public $publicUrl;

    public function __construct($json)
    {
        parent::__construct($json);
        $this->publicUrl = str_replace(' ', '%20', $this->publicUrl);
    }

}