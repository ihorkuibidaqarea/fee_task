<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__. '/../bootstrap/container.php';


try{   

    $UserFee = $app->get('UserFee');
    $fee = $UserFee->getFee();
                   
    var_dump( $fee );
    

} catch (\Exception $e) {

    echo $e->getMessage();

} 


