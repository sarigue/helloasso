<?php

namespace HelloAsso\V5\Traits\Response;

trait Meta
{

    /** @var \HelloAsso\V5\Resource\Meta\Meta */
    public $meta = null;

    protected function format_meta()
    {
        $this->meta = new \HelloAsso\V5\Resource\Meta\Meta($this->meta);
    }
}