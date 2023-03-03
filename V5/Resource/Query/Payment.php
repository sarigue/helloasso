<?php

namespace HelloAsso\V5\Resource\Query;

use HelloAsso\V5\Resource\Payment as PaymentResource;
use HelloAsso\V5\ResourceQuery;
use HelloAsso\V5\Traits\RequestFilter\Date;
use HelloAsso\V5\Traits\RequestFilter\Paginate;
use HelloAsso\V5\Traits\RequestFilter\Sortable;
use HelloAsso\V5\Traits\RequestFilter\State;

class Payment extends ResourceQuery
{
    const RESOURCE_PATH = PaymentResource::RESOURCE_NAME;

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
        return $this->execute($route);
    }

    public function searchFromOrganization()
    {
        $route = '/organizations/' . $this->organization_slug
            . '/payments'
        ;
        return $this->execute($route);
    }


}