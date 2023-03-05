<?php

namespace HelloAsso\V5\Traits\Response;

use Exception;

trait Meta
{

    /** @var \HelloAsso\V5\Resource\Data\Meta */
    public $meta = null;

    /**
     * @throws Exception
     */
    protected function format_meta()
    {
        $this->meta = new \HelloAsso\V5\Resource\Data\Meta($this->meta);
    }
}