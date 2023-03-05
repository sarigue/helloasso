<?php

namespace HelloAsso\V5\Resource\Data;

use DateTime;
use Exception;

abstract class CustomData
{
    const FILES_BASE_URL = 'https://www.helloasso.com/documents/documents_users_souscriptions/';

    const TYPE_RAW  = 'raw';  // Raw (don't parse value)
    const TYPE_BOOL = 'bool';    // Boolean
    const TYPE_BOOL3 = 'bool3';  // Boolean 3 states
    const TYPE_FLOAT = 'float';  // Float (price, etc.)
    const TYPE_INT = 'int';      // Integer
    const TYPE_STR = 'string';  // Text
    const TYPE_DAT = 'date';    // Date, Date-Time
    const TYPE_CUR = 'cur';     // Currency
    const TYPE_FILE = 'file';   // File (URL)

    const REGEX = []; // To override

    protected static $errorHandler = null;

    /**
     * Exception handler
     * @param callable|boolean $handler
     *                             FALSE : don't catch exceptions
     *                             TRUE : continue on exception
     *                             Callble: Exception handler
     */
    public static function setErrorHandler($handler)
    {
        static::$errorHandler = $handler;
    }

    /**
     * @param CustomField[] $data
     * @throws Exception
     */
    public function __construct(array $data)
    {
        foreach ($data as $entry)
        {
            $label = $entry->label();
            $value = $entry->value();

            $definition = [];
            $field = $this->searchField($label, $definition);

            if (empty($field))
            {
                continue;
            }
            try
            {
                $this->$field = $this->parse($value, $definition);
            }
            catch (Exception $e)
            {
                $this->$field = null;
                if (!static::$errorHandler)
                {
                    throw $e;
                }
                if (is_callable(static::$errorHandler))
                {
                    $h = static::$errorHandler; /** @var callable $h */
                    $h($e);
                }
                error_log($e->getMessage());
            }
        }
    }

    /**
     *
     * @param string $label
     * @param array $definition
     * @return string
     */
    protected function searchField($label, &$definition = [])
    {
        foreach (static::REGEX as $field => $definition)
        {
            if (preg_match($definition['regex'] . 'u', $label))
            {
                return $field;
            }
        }
        $definition = [];
        return null;
    }

    /**
     * Transforme en type attendu
     * @param mixed $value
     * @param array $definition
     * @return boolean|number|string|DateTime
     * @throws Exception
     */
    protected function parse($value, array $definition)
    {
        $type = isset($definition['type'])
            ? $definition['type']
            : self::TYPE_RAW
        ;

        switch ($type)
        {
            case self::TYPE_RAW:
                return $value;

            case self::TYPE_BOOL3:
                if ($value === null || $value === '')
                {
                    return null;
                }
                return $this->parseBool($value);

            case self::TYPE_BOOL:
                return $this->parseBool($value);

            case self::TYPE_INT:
                return (int)trim($value);

            case self::TYPE_CUR:
                return (float)number_format(trim($value), 2);

            case self::TYPE_FLOAT:
                return (float)trim($value);

            case self::TYPE_STR:
                return (string)$value;

            case self::TYPE_FILE:
                return static::FILES_BASE_URL . $value;

            case self::TYPE_DAT:
                $format = isset($definition['format'])
                    ? $definition['format']
                    : null;
                return $this->parseDate($value, $format);
        }

        return null;
    }

    /**
     * @param string $value
     * @return boolean
     */
    protected function parseBool($value)
    {
        if ($value === '' || $value === null)
        {
            return false;
        }

        if (is_bool($value))
        {
            return $value;
        }

        if (!is_string($value))
        {
            return (bool)$value;
        }

        $test = substr(strtoupper($value), 0, 1);
        $test = strtoupper($test);

        if ($test == 'N' || $value == '0') // 0, Non, No, Nein, ...
        {
            return false;
        }
        if ($test === 'FALSE')
        {
            return false;
        }

        return true;
    }

    /**
     * Analyse spécifique d'une date
     * @param string $value
     * @param string $format
     * @return DateTime
     * @throws Exception
     * @throws Exception
     */
    protected function parseDate($value, $format = null)
    {

        if (empty($value))
        {
            return null;
        }

        if ($value instanceof DateTime)
        {
            return $value;
        }

        if (isset($format))
        {
            $value = trim($value);
            $datetime = DateTime::createFromFormat($format, $value);
            if (!$datetime)
            {
                throw new Exception(
                    'Error during DateTime::createFromFormat("' . $format . '", "' . $value . '")',
                    500
                );
            }
            return $datetime;
        }

        if (ctype_digit($value))
        {
            $date = new DateTime();
            $date->setTimestamp($value);
            return $date;
        }

        return new DateTime($value);
    }

}
