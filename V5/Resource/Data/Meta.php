<?php

namespace HelloAsso\V5\Resource\Data;

use DateTime;
use Exception;
use HelloAsso\V5\Resource;

class Meta extends Resource
{

    /** @var DateTime */ public $createdAt;
    /** @var DateTime */ public $updatedAt;

    /**
     * @throws Exception
     */
    public function __construct($json)
    {
        parent::__construct($json);
        $this->createdAt = new DateTime($this->createdAt);
        $this->updatedAt = new DateTime($this->updatedAt);
    }

}