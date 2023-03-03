<?php

namespace HelloAsso\V5\Resource\Query;

use HelloAsso\V5\Resource\Item as ItemResource;
use HelloAsso\V5\ResourceQuery;
use HelloAsso\V5\Traits\RequestFilter\Date;
use HelloAsso\V5\Traits\RequestFilter\Paginate;
use HelloAsso\V5\Traits\RequestFilter\Sortable;
use HelloAsso\V5\Traits\RequestFilter\State;
use HelloAsso\V5\Traits\RequestFilter\UserSearchKey;

class Item extends ResourceQuery
{
    const RESOURCE_PATH = ItemResource::RESOURCE_NAME;

    use Date;
    use Paginate;
    use Sortable;
    use UserSearchKey;
    use State;

}