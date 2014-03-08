#!@php_bin@
<?php
use phpdotnet\phd as PhD;

define('__INSTALLDIR__', '@php_dir@' == '@'.'php_dir@' ? dirname(dirname(dirname(dirname(__DIR__)))) : '@php_dir@');
define('DS', DIRECTORY_SEPARATOR);

require __INSTALLDIR__ . DS . 'phpdotnet' . DS . 'phd' . DS . 'Autoloader.php';
require __INSTALLDIR__ . DS . 'phpdotnet' . DS . 'phd' . DS . 'functions.php';

spl_autoload_register(array("phpdotnet\\phd\\Autoloader", "autoload"));

//FIXME Remove this call to Config
PhD\Config::init(array());

function usage()
{
    echo <<<USAGE
Usage:
    phd-ide -d <phd output dir> -f <function> [-a|-p|-l|-s|-S]
    phd-ide -d <phd output dir> -c <class>

Options:
    -d, --dir               PhD output dir.
    -f, --function          The name of the function to obtain information about.
    -c, --class             List the methods of a class.
    -a, --all               Show all information about a function (require -f).
    -p, --params            Show the params of a function (require -f).
    -l, --changelog         Show the changelog of a function (require -f).
    -s, --seealso           Show the see also links of a function (require -f).
    -S, --signature         Show the signature of a function and exit (require -f).
    -h, --help              Show this info and exit.

USAGE;
}

$OPTION['dir']          = NULL;
$OPTION['all']          = NULL;
$OPTION['function']     = NULL;
$OPTION['params']       = NULL;
$OPTION['changelog']    = NULL;
$OPTION['seealso']      = NULL;
$OPTION['signature']    = NULL;
$OPTION['class']        = NULL;
$OPTION['help']         = NULL;

$opts = array(
    'dir:'          => 'd:',
    'class:'        => 'c:',
    'function:'     => 'f:',
    'all'           => 'a',
    'params'        => 'p',
    'changelog'     => 'l',
    'seealso'       => 's',
    'signature'     => 'S',
    'help'          => 'h',
);

$options = @getopt(implode($opts), array_keys($opts));

foreach ($options as $k => $v) {
    switch ($k) {
    case 'd':
    case 'dir':
        if (is_array($v)) {
            trigger_error('Is not possible to pass the --dir option more then once', E_USER_ERROR);
        }
        $OPTION['dir'] = $v;
        break;
    case 'f':
    case 'function':
        if (is_array($v)) {
            trigger_error('Is not possible to pass the --function option more then once', E_USER_ERROR);
        }
        $OPTION['function'] = $v;
        break;
    case 'c':
    case 'class':
        if (is_array($v)) {
            trigger_error('Is not possible to pass the --class option more then once', E_USER_ERROR);
        }
        $OPTION['class'] = $v;
        break;
    case 'a':
    case 'all':
        $OPTION['all'] = true;
        break;
    case 'p':
    case 'params':
        $OPTION['params'] = true;
        break;
    case 'l':
    case 'changelog':
        $OPTION['changelog'] = true;
        break;
    case 's':
    case 'seealso':
        $OPTION['seealso'] = true;
        break;
    case 'S':
    case 'signature':
        $OPTION['signature'] = true;
        break;
    case 'h':
    case 'help':
        $OPTION['help'] = true;
        break;
    default:
        trigger_error('Invalid Option: ' . $k, E_USER_ERROR);
    }
}

if ($OPTION['help'] === true || !$options) {
    usage();
    exit(0);
}

if ($OPTION['dir'] == NULL) {
    trigger_error('You must specify the PhD output directory with the --dir option.', E_USER_ERROR);
}

if ($OPTION['function'] == NULL && $OPTION['class'] == NULL) {
    trigger_error('You must pass either --class or --function options.', E_USER_ERROR);
}

$api = new PhD\Package_IDE_API($OPTION['dir']);

if ($OPTION['class'] != NULL) {
    $methods = $api->getMethodsByClass($OPTION['class']);
    if ($methods == NULL) {
        trigger_error('Invalid Class name: ' . $OPTION['class'], E_USER_ERROR);
    }
    foreach ($methods as $method) {
        echo $method . PHP_EOL;
    }
    exit(0);
}

$function = $api->getFunctionByName($OPTION['function']);

if ($function == NULL) {
    trigger_error('Invalid Function: ' . $OPTION['function'], E_USER_ERROR);
}

if ($OPTION['signature'] === true) {
    echo $function . PHP_EOL;
    exit(0);
}

echo 'Name: '               . $function->getName()              . PHP_EOL;
echo 'Id: '                 . $function->getManualID()          . PHP_EOL;
echo 'Version: '            . $function->getVersion()           . PHP_EOL;
echo 'Purpose: '            . $function->getPurpose()           . PHP_EOL;
echo 'Return Type: '        . $function->getReturnType()        . PHP_EOL;
echo 'Return Description: ' . $function->getReturnDescription() . PHP_EOL;

if ($OPTION['all'] === true || $OPTION['params'] === true) {
    echo 'Params: ';
    foreach ($function->getParams() as $param) {
        echo PHP_EOL;
        echo "\tString: "       . $param                                        . PHP_EOL;
        echo "\tName: "         . $param->getName()                             . PHP_EOL;
        echo "\tType: "         . $param->getType()                             . PHP_EOL;
        echo "\tOptional: "     . ($param->isOptional() ? 'true' : 'false')     . PHP_EOL;
        echo "\tInitializer: "  . $param->getInitializer()                      . PHP_EOL;
        echo "\tDescription: "  . $param->getDescription()                      . PHP_EOL;
    }
}

if ($OPTION['all'] === true || $OPTION['changelog'] === true) {
    echo 'Changelog: ';
    foreach ($function->getChangelogEntries() as $entry) {
        echo PHP_EOL;
        echo "\tVersion: "         . $entry['version']                             . PHP_EOL;
        echo "\tChange:  "         . $entry['change']                              . PHP_EOL;
    }
}

if ($OPTION['all'] === true || $OPTION['seealso'] === true) {
    echo 'See Also: ';
    foreach ($function->getSeeAlsoEntries() as $entry) {
        echo PHP_EOL;
        echo "\tName: "         . $entry['name']            . PHP_EOL;
        echo "\tType: "         . $entry['type']            . PHP_EOL;
        echo "\tDescription: "  . $entry['description']     . PHP_EOL;
    }
}

/*
 * vim600: sw=4 ts=4 syntax=php et
 * vim<600: sw=4 ts=4
 */
