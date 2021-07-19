<?php
    require_once( __DIR__.'/../vendor/autoload.php');

    $builder = new \DI\ContainerBuilder();
       
    $builder->useAutowiring(false);
    $builder->useAnnotations(false);

    $builder->addDefinitions(require __DIR__ . '/dependencies.php');

    return $builder->build();