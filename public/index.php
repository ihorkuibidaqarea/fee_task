<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__. '/../bootstrap/container.php';


try{   

    $UserFee = $app->get('UserFee');                 
    var_dump($UserFee->getFee());
    

} catch (\Exception $e) {

    echo $e->getMessage();

} 


