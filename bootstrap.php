<?php

require_once "vendor/autoload.php";
require_once "configbot.php";
use app\classes\Bind;
use app\models\Connection;

$config = require "config.php";

date_default_timezone_set('America/Sao_Paulo');

Bind::set('config', $config);
Bind::set('connection', Connection::connect());