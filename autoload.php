<?php

namespace HelloAsso;

function autoload($className)
{
    if (substr($className, 0, strlen(__NAMESPACE__)) != __NAMESPACE__)
    {
        return;
    }

    $className = preg_replace(
        '/^' . __NAMESPACE__ . '\\\\/',
        '',
        $className
    );

    $className = ltrim($className, '\\');
    $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';

    require $fileName;
}

spl_autoload_register('HelloAsso\autoload');
