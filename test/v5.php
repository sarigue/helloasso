<?php
require_once '../autoload.php';
require_once './config.php';

use HelloAsso\V5\Api\AuthError;
use HelloAsso\V5\Api\ResponseError;
use HelloAsso\V5\HelloAsso;
use HelloAsso\V5\Resource\Form;

try
{
    // Chack auth

    HelloAsso::initialize()
        ->setClient(CLIENT_ID, CLIENT_SECRET)
        ->setOrganization(ORGANIZATION_SLUG)
        ->authenticate()
    ;

    // Check payment search

    $result =
    \HelloAsso\V5\Resource\Query\Payment::create()
        ->setFromDate(date('Y').'-01-01')
        ->search()
        ->throwException()
        ->setResourceClass(\HelloAsso\V5\Resource\Payment::class)
        ;

    echo '------ PAYMENTS SEARCH' . PHP_EOL;
    var_export($result->getCollection());
    echo PHP_EOL;
    echo PHP_EOL;

    // Getter

    $result =
        \HelloAsso\V5\Resource\Query\Form::create()
            ->get(TEST_FORM_SLUG, TEST_FORM_TYPE)
            ->throwException()
            ->setResourceClass(Form::class)
            ->getResource()
    ;
    $result->refresh();

    echo '------ FORM' . PHP_EOL;
    var_export($result);
    echo PHP_EOL;
}
catch (ResponseError $e)
{
    echo '/!\\ RESPONSE ERROR /!\\' . PHP_EOL;
    echo '----------------------' . PHP_EOL;
    var_export($e->dump());
    echo PHP_EOL;
    echo PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}
catch (AuthError $e)
{
    echo '/!\\ AUTH ERROR /!\\' . PHP_EOL;
    echo '------------------' . PHP_EOL;
    var_export($e->dump());
    echo PHP_EOL;
    echo PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}
catch (Exception $e)
{
    echo '/!\\ EXCEPTION /!\\' . PHP_EOL;
    echo '-----------------' . PHP_EOL;
    echo $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
    echo PHP_EOL;
    var_export($e);
}
