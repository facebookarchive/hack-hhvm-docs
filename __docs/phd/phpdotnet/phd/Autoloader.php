<?php
namespace phpdotnet\phd;

require __DIR__ . DIRECTORY_SEPARATOR . 'Config.php';

class Autoloader
{
    public static function autoload($name)
    {
        // Only try autoloading classes we know about (i.e. from our own namespace)
        if (strncmp('phpdotnet\phd\\', $name, 14) === 0) {
            $filename = DIRECTORY_SEPARATOR . str_replace(array('\\', '_'), DIRECTORY_SEPARATOR, $name) . '.php';
            foreach(Config::package_dirs() as $dir) {
                $file = $dir . $filename;

                // Using fopen() because it has use_include_path parameter.
                if (!$fp = @fopen($file, 'r', true)) {
                    continue;
                }

                fclose($fp);
                require $file;

                return false;
            }
            v('Cannot find file for %s: %s', $name, $file, E_USER_ERROR);
        }

        return false;
    }
}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

