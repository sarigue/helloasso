<?php

namespace HelloAsso\V5\Resource\Query;

use HelloAsso\V5\Resource\Form as FormResource;
use HelloAsso\V5\ResourceQuery;
use HelloAsso\V5\Traits\RequestFilter\Date;
use HelloAsso\V5\Traits\RequestFilter\Paginate;
use HelloAsso\V5\Traits\RequestFilter\Sortable;
use HelloAsso\V5\Traits\RequestFilter\State;
use HelloAsso\V5\Traits\RequestFilter\UserSearchKey;

class Form extends ResourceQuery
{
    const RESOURCE_PATH = FormResource::RESOURCE_NAME;

    use Paginate;
    use UserSearchKey;
    use State;

    /**
     * @param string $form_types
     * @return $this
     */
    public function setFormTypes($form_types)
    {
        $this->addParam('formTypes', $form_types);
        return $this;
    }

    /**
     * @param string $slug
     * @param string $type
     * @return \HelloAsso\V5\Api\Response
     * @throws \HelloAsso\V5\Api\ResponseError
     */
    public function get($slug, $type)
    {
        $route = 'organizations/' . $this->organization_slug
            . '/' . $this->resource_path. '/' . $type
            . '/' . $slug
            . '/public'
        ;
        return $this->execute($route);
    }

}