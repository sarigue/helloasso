<?php 
namespace HelloAsso\V5;

use DateTime;
use Exception;
use HelloAsso\V5\Resource\Data\Meta as MetaResource;
use HelloAsso\V5\Traits\Response\Meta as MetaTrait;
use stdClass;

/**
 * Abstract class for HelloAsso resources
 *
 * @author fraoult
 * @license MIT
 */
abstract class Resource
{
    const RESOURCE_NAME = '';

    /**
     * @param stdClass|array $json
     * @throws Exception
     * @throws Exception
     */
	public function __construct($json)
	{
        // set property

		foreach($json as $field => $value)
		{
            if ($field == 'date')
            {
                $this->date = new DateTime($value);
                continue;
            }
            if ($field == 'meta' && $this->useMeta())
            {
                $this->meta = new MetaResource($value);
                continue;
            }

            $this->$field = $value;
		}
	}

    /**
     * check if class use trait "Meta"
     * @return bool
     */
    private function useMeta()
    {
        $traits = class_uses($this);
        if (empty($traits))
        {
            return false;
        }
        $use_meta = isset($traits[MetaTrait::class]);
        return $use_meta
            && method_exists($this, 'format_meta')
            && property_exists($this, 'meta')
        ;
    }

    /**
     * @param mixed $value
     * @param string $classname
     * @return void
     */
    protected function convert(&$value, $classname)
    {
        if (empty($value))
        {
            return;
        }

        if (!is_array($value))
        {
            $value = new $classname($value);
            return;
        }

        foreach($value as $i => $v)
        {
            $value[$i] = new $classname($v);
        }
    }
}
