<?php

namespace HelloAsso\V5\Traits\RequestFilter;

trait State
{

    abstract protected function addParam($name, $value);

    /**
     * Filter by states
     * @param string $state
     * @return $this
     */
    public function setState($state)
    {
        $this->addParam('states', $state);
        return $this;
    }

}