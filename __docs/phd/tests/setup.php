<?php
namespace phpdotnet\phd;
/* $Id$ */

function autoload($name) {
    $file = __DIR__ . "/../" . str_replace(array('\\', '_'), '/', $name) . '.php';
    if (!$fp = fopen($file,'r', true)) {
        throw new \Exception('Cannot find file for ' . $name . ': ' . $file);
    }   
    fclose($fp);
    require $file;

}
spl_autoload_register(__NAMESPACE__ . '\\autoload');

require_once __DIR__ . "/../phpdotnet/phd/functions.php";
require_once __DIR__ . "/../phpdotnet/phd/Config.php";

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/
