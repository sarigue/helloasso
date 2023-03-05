<?php

namespace HelloAsso\V5\Resource\Query;

use Exception;
use HelloAsso\V5\Api\Response;
use HelloAsso\V5\Api\ResponseError;
use HelloAsso\V5\Resource\Form as FormResource;
use HelloAsso\V5\ResourceQuery;
use HelloAsso\V5\Traits\RequestFilter\Paginate;
use HelloAsso\V5\Traits\RequestFilter\State;
use HelloAsso\V5\Traits\RequestFilter\UserSearchKey;

class Form extends ResourceQuery
{
    const RESOURCE_CLASS = FormResource::class;

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
     * @return Response
     * @throws ResponseError
     * @throws Exception
     * @noinspection PhpParameterNameChangedDuringInheritanceInspection
     */
    public function get($slug, $type = null)
    {
        if (empty($type))
        {
            throw new Exception('$type argument missing');
        }
        $route = 'organizations/' . $this->organization_slug
            . '/' . $this->resource_path. '/' . $type
            . '/' . $slug
            . '/public'
        ;
        return $this->execute($route);
    }

}