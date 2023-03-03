<?php

namespace HelloAsso\V5\Resource\Meta;

use HelloAsso\V5\Resource;

class User extends Resource
{

    /** @var string */ public $firstName;
    /** @var string */ public $lastName;

    public function __construct($json)
    {
        parent::__construct($json);
    }

}