<?php

namespace HelloAsso\V5\Traits\RequestFilter;

trait UserSearchKey
{

    abstract protected function addParam($name, $value);

    /**
     * Filter by states
     * @param string $state
     * @return $this
     */
    public function setUserSearchKey($state)
    {
        $this->addParam('userSearchKey', $state);
        return $this;
    }

}