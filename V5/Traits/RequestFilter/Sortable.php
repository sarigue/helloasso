<?php

namespace HelloAsso\V5\Traits\RequestFilter;

trait Sortable
{

    abstract protected function addParam($name, $value);

    /**
     * @return $this
     */
    public function setSortAscendant()
    {
        $this->addParam('sortOrder', 'Asc');
        return $this;
    }

    /**
     * @return $this
     */
    public function setSortDescendant()
    {
        $this->addParam('sortOrder', 'Desc');
        return $this;
    }

    /**
     * @return $this
     */
    public function setSortDefault()
    {
        $this->addParam('sortOrder', null);
        return $this;
    }


    /**
     * @param string $sortField
     * @return $this
     */
    public function setSortField($sortField)
    {
        $this->addParam('sortField', $sortField);
        return $this;
    }


}