<?php
    require_once __DIR__ .'/../vendor/autoload.php';

    $builder = new \DI\ContainerBuilder();
       
    $builder->useAutowiring(false);
    // $builder->useAnnotations(true);

    $builder->addDefinitions(require_once __DIR__ . '/dependencies.php');

    return $builder->build();
