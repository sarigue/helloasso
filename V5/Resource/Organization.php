<?php

namespace HelloAsso\V5\Resource;


use Exception;
use HelloAsso\V5\Api\ResponseError;
use HelloAsso\V5\Resource;
use HelloAsso\V5\Traits\Queryable;
use HelloAsso\V5\Traits\Response\Meta;

/**
 * Paiement HelloAsso
 * 
 * @author fraoult
 * @license MIT
 */
class Organization extends Resource
{
    use Queryable, Meta;

    const RESOURCE_NAME = 'organizations';

    const TYPE_ASSO_1901     = 'Association1901';
    const TYPE_ASSO_1901_RIG = 'Association1901Rig';
    const TYPE_ASSO_1901_RUP = 'Association1901Rup';
    const TYPE_ASSO_1905     = 'Association1905';
    const TYPE_ASSO_1905_RUP = 'Association1905Rup';
    const TYPE_ASSO_1908     = 'Association1908';
    const TYPE_ASSO_1908_RIG = 'Association1908Rig';
    const TYPE_ASSO_1908_RUP = 'Association1908Rup';
    const TYPE_FONDATION_RUP = 'FondationRup';
    const TYPE_FONDATION_DONATION = 'FondDotation';
    const TYPE_FONDATION_EGIDE   = 'FondationSousEgide';
    const TYPE_FONDATION_SCIENCE = 'FondationScientifique';
    const TYPE_FONDATION_PART    = 'FondationPartenariale';
    const TYPE_FONDATION_UNIV    = 'FondationUniversitaire';
    const TYPE_FONDATION_HOSP    = 'FondationHospitaliere';
    const TYPE_ENTREPRISE        = 'Entreprise';
    const TYPE_COOP              = 'Cooperative';
    const TYPE_ETABLISSEMENT     = 'Etablissement';

    const TYPELIST_ASSO_1901 = [
        self::TYPE_ASSO_1901,
        self::TYPE_ASSO_1901_RIG,
        self::TYPE_ASSO_1901_RUP
    ];
    const TYPELIST_ASSO_1905 = [
        self::TYPE_ASSO_1905,
        self::TYPE_ASSO_1905_RUP
    ];
    const TYPELIST_ASSO_1908 = [
        self::TYPE_ASSO_1908,
        self::TYPE_ASSO_1908_RIG,
        self::TYPE_ASSO_1908_RUP
    ];

    const TYPELIST_ASSO = self::TYPELIST_ASSO_1901
        + self::TYPELIST_ASSO_1905
        + self::TYPELIST_ASSO_1908
    ;

    const TYPELIST_FONDATION = [
        self::TYPE_FONDATION_DONATION,
        self::TYPE_FONDATION_HOSP,
        self::TYPE_FONDATION_EGIDE,
        self::TYPE_FONDATION_PART,
        self::TYPE_FONDATION_RUP,
        self::TYPE_FONDATION_SCIENCE,
        self::TYPE_FONDATION_UNIV,
    ];

    const TYPELIST_RIG = [
        self::TYPE_ASSO_1901_RIG,
        self::TYPE_ASSO_1908_RIG,
    ];

    const TYPELIST_RUP = [
        self::TYPE_ASSO_1901_RUP,
        self::TYPE_ASSO_1905_RUP,
        self::TYPE_ASSO_1908_RUP,
        self::TYPE_FONDATION_RUP
    ];


    /** @var bool      */ public $isAuthenticated;
    /** @var bool      */ public $fiscalReceiptEligibility;
    /** @var bool      */ public $fiscalReceiptIssuanceEnabled;
    /** @var string    */ public $type;
    /** @var string    */ public $category;
    /** @var string    */ public $rnaNumber;
    /** @var string    */ public $logo;
    /** @var string    */ public $name;
    /** @var string    */ public $role;
    /** @var string    */ public $city;
    /** @var string    */ public $zipCode;
    /** @var string    */ public $description;
    /** @var string    */ public $url;
    /** @var string    */ public $organizationSlug;

    public function __construct($json)
    {
        parent::__construct($json);
        if ($this->logo)
        {
            $this->logo = str_replace(' ', '%20', $this->logo);
        }
    }

    public function isAssociation()
    {
        return in_array($this->type, self::TYPELIST_ASSO);
    }

    public function getAssociationYear()
    {
        if (in_array($this->type, self::TYPELIST_ASSO_1901))
        {
            return 1901;
        }
        if (in_array($this->type, self::TYPELIST_ASSO_1905))
        {
            return 1905;
        }
        if (in_array($this->type, self::TYPELIST_ASSO_1908))
        {
            return 1908;
        }
        return null;
    }

    public function isFondation()
    {
        return in_array($this->type, self::TYPELIST_FONDATION);
    }

    public function isRUP()
    {
        return in_array($this->type, self::TYPELIST_RUP);
    }

    public function isRIG()
    {
        return in_array($this->type, self::TYPELIST_RIG);
    }

    /**
     * @return $this
     * @throws ResponseError
     * @throws Exception
     * @see Queryable::refresh()
     */
    public function refresh()
    {
        $this->__construct(
            static::getResponse($this->organizationSlug)
                ->getData()
        );
        return $this;
    }

}
