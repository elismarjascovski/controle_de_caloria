<?php

require '../bootstrap.php';

use core\Parameters;
use core\Controller;
use core\Method;

try{
    $controller = new Controller;
    $controller = $controller->load();

    $method = new Method;
    $method = $method->load($controller);

    $parameters = new Parameters;
    $parameters = $parameters->load();


    $controller->$method($parameters);

}catch(\Exception $e) {
    dd($e->getMessage());
}