<?php

namespace HelloAsso\V5\Resource;


use DateTime;
use Exception;
use HelloAsso\V5\Api\Response;
use HelloAsso\V5\Api\ResponseError;
use HelloAsso\V5\Resource;
use HelloAsso\V5\Resource\Data\Banner;
use HelloAsso\V5\Resource\Data\Tiers;
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
    /** @var DateTime  */ public $startDate;
    /** @var DateTime  */ public $endDate;
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
        try {
            $this->startDate = new DateTime($this->startDate);
        } catch (Exception $e) {
        }
        try {
            $this->endDate = new DateTime($this->endDate);
        } catch (Exception $e) {
        }
        if ($this->organizationLogo)
        {
            $this->organizationLogo = str_replace(' ', '%20', $this->organizationLogo);
        }
    }

    /**
     * @param string $slug
     * @param string $type
     * @return Response
     * @throws ResponseError
     * @see Queryable::getResponseForId()
     * @noinspection PhpParameterNameChangedDuringInheritanceInspection
     */
    public static function getResponseForId($slug, $type)
    {
        return Query\Form::create()
            ->get($slug, $type)
            ->throwException()
        ;
    }

    /**
     * @param string $slug
     * @param string $type
     * @return void
     * @throws ResponseError
     * @throws Exception
     * @see Queryable::get()
     * @noinspection PhpParameterNameChangedDuringInheritanceInspection
     */
    public static function get($slug, $type)
    {
        static::getResponseForId($slug, $type)
            ->setResourceClass(static::class)
            ->getResource();
    }

    /**
     * @return $this
     * @throws Exception
     * @see Queryable::refresh()
     */
    public function refresh()
    {
        try {
            $this->__construct(
                static::getResponseForId($this->formSlug, $this->formType)
                    ->getData()
            );
        } catch (ResponseError $e) {
        }
        return $this;
    }

}
