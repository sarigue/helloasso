<?php

namespace HelloAsso\V5\Resource\Query;

use HelloAsso\V5\Api\Authentication;
use HelloAsso\V5\Resource\Form as FormResource;
use HelloAsso\V5\Resource\Order as OrderResource;
use HelloAsso\V5\Resource\Payment as PaymentResource;
use HelloAsso\V5\ResourceQuery;
use HelloAsso\V5\Traits\RequestFilter\Paginate;
use HelloAsso\V5\Traits\RequestFilter\State;
use HelloAsso\V5\Traits\RequestFilter\UserSearchKey;

class Order extends ResourceQuery
{
    const RESOURCE_CLASS = OrderResource::class;

    use Paginate;
    use State;
    use UserSearchKey;

}