<?php

namespace HelloAsso\V5\Resource\Query;

use HelloAsso\V5\Resource\Order as OrderResource;
use HelloAsso\V5\ResourceQuery;
use HelloAsso\V5\Traits\RequestFilter\Date;
use HelloAsso\V5\Traits\RequestFilter\Paginate;
use HelloAsso\V5\Traits\RequestFilter\Sortable;
use HelloAsso\V5\Traits\RequestFilter\State;
use HelloAsso\V5\Traits\RequestFilter\UserSearchKey;

class Order extends ResourceQuery
{
    const RESOURCE_PATH = OrderResource::RESOURCE_NAME;

    use Paginate;
    use State;
    use UserSearchKey;

}