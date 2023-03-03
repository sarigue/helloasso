<?php

namespace HelloAsso\V5\Resource;


use HelloAsso\V5\Resource;
use HelloAsso\V5\Resource\Meta\Banner;
use HelloAsso\V5\Resource\Meta\Tiers;
use HelloAsso\V5\Traits\Queryable;
use HelloAsso\V5\Traits\Response\Meta;

/**
 * Paiement HelloAsso
 * 
 * @author fraoult
 * @license MIT
 */
class Form extends Resource
{
    use Queryable, Meta;

    const RESOURCE_NAME = 'forms';

    const TYPE_CHECKOUT = 'Checkout';
    const TYPE_DONATION = 'Donation';
    const TYPE_EVENT = 'Event';
    const TYPE_MEMBERSHIP = 'Membership';
    const TYPE_PAYMENTFORM = 'PaymentForm';


    /** @var string    */ public $organizationLogo;
    /** @var string    */ public $organizationName;
    /** @var Tiers     */ public $tiers;
    /** @var string    */ public $activityType;
    /** @var int       */ public $activityTypeId;
    /** @var string    */ public $validityType;
    /** @var Banner    */ public $banner;
    /** @var string    */ public $currency;
    /** @var string    */ public $description;
    /** @var \DateTime */ public $startDate;
    /** @var \DateTime */ public $endDate;
    /** @var string    */ public $state;
    /** @var string    */ public $title;
    /** @var string    */ public $widgetButtonUrl;
    /** @var string    */ public $widgetFullUrl;
    /** @var string    */ public $widgetVignetteHorizontalUrl;
    /** @var string    */ public $widgetVignetteVerticalUrl;
    /** @var string    */ public $formSlug;
    /** @var string    */ public $formType;
    /** @var string    */ public $url;
    /** @var string    */ public $organizationSlug;

    public function __construct($json)
    {
        parent::__construct($json);
        $this->convert($this->banner, Banner::class);
        $this->convert($this->tiers, Tiers::class);
        $this->startDate = new \DateTime($this->startDate);
        $this->endDate = new \DateTime($this->endDate);
        if ($this->organizationLogo)
        {
            $this->organizationLogo = str_replace(' ', '%20', $this->organizationLogo);
        }
    }

    /**
     * @see Queryable::getResponse()
     * @param string $slug
     * @param string $type
     * @return \HelloAsso\V5\Api\Response
     * @throws \HelloAsso\V5\Api\ResponseError
     */
    public static function getResponse($slug, $type)
    {
        return Resource\Query\Form::create()
            ->get($slug, $type)
            ->throwException()
        ;
    }

    /**
     * @see Queryable::get()
     * @param string $slug
     * @param string $type
     * @return void
     * @throws \HelloAsso\V5\Api\ResponseError
     */
    public static function get($slug, $type)
    {
        static::getResponse($slug, $type)
            ->setResourceClass(static::class)
            ->getResource();
    }

    /**
     * @see Queryable::refresh()
     * @return $this
     */
    public function refresh()
    {
        $this->__construct(
            static::getResponse($this->formSlug, $this->formType)
                ->getData()
        );
        return $this;
    }

}
