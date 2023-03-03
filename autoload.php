<?php

namespace HelloAsso;

function autoload($className)
{
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';

    if ($lastNsPos = strrpos($className, '\\'))
    {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace(
            '\\',
            DIRECTORY_SEPARATOR,
            $namespace
            )
            . DIRECTORY_SEPARATOR
        ;
    }

    $fileName .= str_replace(
        '_',
        DIRECTORY_SEPARATOR,
        $className
        )
        . '.php'
    ;

    $fileName = preg_replace(
        '/^' . __NAMESPACE__ . '\//',
        '', $fileName
    );

    require $fileName;
}

spl_autoload_register('HelloAsso\autoload');
