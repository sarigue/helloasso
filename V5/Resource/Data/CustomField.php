<?php

namespace HelloAsso\V5\Resource\Data;


use DateTime;
use Exception;
use HelloAsso\V5\Resource;

/**
 * Paiement HelloAsso
 * 
 * @author fraoult
 * @license MIT
 */
class CustomField extends Resource
{
    const TYPE_DATE     = 'Date';
    const TYPE_TEXT     = 'TextInput';
    const TYPE_FREETEXT = 'FreeText';
    const TYPE_CHOICE   = 'ChoiceList';
    const TYPE_FILE     = 'File';
    const TYPE_YESNO    = 'YesNo';
    const TYPE_PHONE    = 'Phone';
    const TYPE_ZIPCODE  = 'Zipcode';
    const TYPE_NUMBER   = 'Number';


    /** @var string    */ public $name;
    /** @var string    */ public $type;
    /** @var string    */ public $answer;

	public function __construct($json)
	{
        parent::__construct($json);
	}

    public function label()
    {
        return $this->name;
    }

    /**
     * @throws Exception
     */
    public function value()
    {
        switch ($this->type)
        {
            case self::TYPE_YESNO:
                if (empty($this->answer))
                {
                    return null;
                }
                $char = strtolower(substr($this->answer, 0, 1));
                return $char != 'n' && $char != '0';

            case self::TYPE_DATE:
                if (empty($this->answer))
                {
                    return null;
                }
                if (ctype_digit($this->answer))
                {
                    $date = new DateTime();
                    $date->setTimestamp($this->answer);
                    return $date;
                }
                return DateTime::createFromFormat('d/m/Y', $this->answer);

            case self::TYPE_NUMBER:
                if ($this->answer == '')
                {
                    return null;
                }
                if (ctype_digit($this->answer))
                {
                    return (int)trim($this->answer);
                }
                return (float)trim($this->answer);

            default:
                return $this->answer;
        }
    }

}
