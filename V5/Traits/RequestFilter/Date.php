<?php

namespace HelloAsso\V5\Traits\RequestFilter;

trait Date
{

    abstract protected function addParam($name, $value);

    /**
     * Numéro de page à chercher
     * @param string $from_date Asc, Desc
     * @return $this
     */
    public function setFromDate($from_date)
    {
        $this->addParam('from', $from_date);
        return $this;
    }

    /**
     * Nombre de résultats par page
     * @param string $to_date
     * @return $this
     */
    public function setToDate($to_date)
    {
        $this->addParam('to', $to_date);
        return $this;
    }

}