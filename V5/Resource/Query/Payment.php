<?php

namespace HelloAsso\V5\Resource\Query;

use HelloAsso\V5\Api\Authentication;
use HelloAsso\V5\Resource\Order as OrderResource;
use HelloAsso\V5\Resource\Payment as PaymentResource;
use HelloAsso\V5\ResourceQuery;
use HelloAsso\V5\Traits\RequestFilter\Date;
use HelloAsso\V5\Traits\RequestFilter\Paginate;
use HelloAsso\V5\Traits\RequestFilter\Sortable;
use HelloAsso\V5\Traits\RequestFilter\State;

class Payment extends ResourceQuery
{
    const RESOURCE_CLASS = PaymentResource::class;

    use Date;
    use Paginate;
    use Sortable;
    use State;


    public function searchFromForm($formType, $formSlug)
    {
        $route = '/organizations/' . $this->organization_slug
            . '/forms/' . $formType
            . '/' . $formSlug
            . '/payments'
        ;
        return $this->execute($route)->setResourceClass(static::RESOURCE_CLASS);
    }

    public function searchFromOrganization()
    {
        $route = '/organizations/' . $this->organization_slug
            . '/payments'
        ;
        return $this->execute($route)->setResourceClass(self::RESOURCE_CLASS);
    }


}