<?php

namespace HelloAsso\V5\Resource\Data;


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
                    return new \DateTime($this->answer);
                }
                return new \DateTime(strtotime($this->answer));

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
                return (string)$this->answer;
        }
    }

}
