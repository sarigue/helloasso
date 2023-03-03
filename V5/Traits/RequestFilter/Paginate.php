<?php

namespace HelloAsso\V5\Traits\RequestFilter;

trait Paginate
{


    abstract protected function addParam($name, $value);

    /**
     * @param string $continuationToken
     * @return $this
     */
    public function setContinuationToken($continuationToken)
    {
        $this->addParam('continuationToken', $continuationToken);
        return $this;
    }

    /**
     * @param int $pageIndex
     * @return $this
     */
    public function setPageIndex($pageIndex)
    {
        $this->addParam('pageIndex', $pageIndex);
        return $this;
    }

    /**
     * @param int $pageSize
     * @return $this
     */
    public function setPageSize($pageSize)
    {
        $this->addParam('pageSize', $pageSize);
        return $this;
    }

}