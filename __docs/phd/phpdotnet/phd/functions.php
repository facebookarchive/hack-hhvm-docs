<?php
namespace phpdotnet\phd;
/* $Id$ */

/* {{{ PhD error & message handler */

// PhD verbose flags
define('VERBOSE_ERRORS',                 (E_USER_DEPRECATED            << 1) - 1);

// Informationals
define('VERBOSE_INDEXING',               E_USER_DEPRECATED             << 1);
define('VERBOSE_FORMAT_RENDERING',       VERBOSE_INDEXING              << 1);
define('VERBOSE_THEME_RENDERING',        VERBOSE_FORMAT_RENDERING      << 1);
define('VERBOSE_RENDER_STYLE',           VERBOSE_THEME_RENDERING       << 1);
define('VERBOSE_PARTIAL_READING',        VERBOSE_RENDER_STYLE          << 1);
define('VERBOSE_PARTIAL_CHILD_READING',  VERBOSE_PARTIAL_READING       << 1);
define('VERBOSE_TOC_WRITING',            VERBOSE_PARTIAL_CHILD_READING << 1);
define('VERBOSE_CHUNK_WRITING',          VERBOSE_TOC_WRITING           << 1);
define('VERBOSE_MESSAGES',               VERBOSE_CHUNK_WRITING         << 1);

define('VERBOSE_INFO',                   ((VERBOSE_MESSAGES            << 1) - 1) & ~VERBOSE_ERRORS);


// Warnings
define('VERBOSE_NOVERSION',              VERBOSE_MESSAGES              << 1);
define('VERBOSE_BROKEN_LINKS',           VERBOSE_NOVERSION             << 1);
define('VERBOSE_MISSING_ATTRIBUTES',     VERBOSE_BROKEN_LINKS          << 1);
define('VERBOSE_OLD_LIBXML',             VERBOSE_MISSING_ATTRIBUTES    << 1);

define('VERBOSE_WARNINGS',               ((VERBOSE_OLD_LIBXML  << 1) - 1) & ~VERBOSE_ERRORS & ~VERBOSE_INFO);


define('VERBOSE_ALL',                    (VERBOSE_OLD_LIBXML   << 1) - 1);
define('VERBOSE_DEFAULT',                (VERBOSE_ALL^(VERBOSE_PARTIAL_CHILD_READING|VERBOSE_CHUNK_WRITING|VERBOSE_WARNINGS|VERBOSE_TOC_WRITING)));

$olderrrep = error_reporting();
error_reporting($olderrrep | VERBOSE_DEFAULT);

/* {{{ Print info messages: v("printf-format-text" [, $arg1, ...], $verbose-level) */
// trigger_error() only accepts E_USER_* errors :(
function v($msg, $errno) {
    $args = func_get_args();
    $errno = array_pop($args);

    $msg = vsprintf(array_shift($args), $args);

    $bt = debug_backtrace();
    return errh($errno, $msg, $bt[0]["file"], $bt[0]["line"]);
}
/* }}} */

/* {{{ Function to get a color escape sequence */
function term_color($text, $color)
{
    return Config::color_output() && $color !== false ? "\033[" . $color . "m" . $text . "\033[m" : $text;
}
/* }}} */

/* {{{ Removes a directory, recursively. Taken from: http://php.net/is_link */
function removeDir($path) {
    $dir = new \DirectoryIterator($path);
    foreach ($dir as $fileinfo) {
        if ($fileinfo->isFile() || $fileinfo->isLink()) {
            unlink($fileinfo->getPathName());
        } elseif (!$fileinfo->isDot() && $fileinfo->isDir()) {
            removeDir($fileinfo->getPathName());
        }
    }
    rmdir($path);
}
/* }}} */

/* {{{ The PhD errorhandler */
function errh($errno, $msg, $file, $line, $ctx = null) {
    static $err = array(
        // PHP Triggered Errors
        E_DEPRECATED                  => 'E_DEPRECATED          ',
        E_RECOVERABLE_ERROR           => 'E_RECOVERABLE_ERROR   ',
        E_STRICT                      => 'E_STRICT              ',
        E_WARNING                     => 'E_WARNING             ',
        E_NOTICE                      => 'E_NOTICE              ',

        // User Triggered Errors
        E_USER_ERROR                  => 'E_USER_ERROR          ',
        E_USER_WARNING                => 'E_USER_WARNING        ',
        E_USER_NOTICE                 => 'E_USER_NOTICE         ',
        E_USER_DEPRECATED             => 'E_USER_DEPRECATED     ',

        // PhD informationals
        VERBOSE_INDEXING              => 'Indexing              ',
        VERBOSE_FORMAT_RENDERING      => 'Rendering Format      ',
        VERBOSE_THEME_RENDERING       => 'Rendering Theme       ',
        VERBOSE_RENDER_STYLE          => 'Rendering Style       ',
        VERBOSE_PARTIAL_READING       => 'Partial Reading       ',
        VERBOSE_PARTIAL_CHILD_READING => 'Partial Child Reading ',
        VERBOSE_TOC_WRITING           => 'Writing TOC           ',
        VERBOSE_CHUNK_WRITING         => 'Writing Chunk         ',
        VERBOSE_MESSAGES              => 'Heads up              ',

        // PhD warnings
        VERBOSE_NOVERSION             => 'No version information',
        VERBOSE_BROKEN_LINKS          => 'Broken links          ',
        VERBOSE_OLD_LIBXML            => 'Old libxml2           ',
        VERBOSE_MISSING_ATTRIBUTES    => 'Missing attributes    ',
    );
    static $recursive = false;

    // Respect the error_reporting setting
    if (!(error_reporting() & $errno)) {
        return false;
    }

    // Recursive protection
    if ($recursive) {
        // Thats bad.. lets print a backtrace right away
        debug_print_backtrace();
        // Fallback to the default errorhandler
        return false;
    }
    $recursive = true;

    $time = date(Config::date_format());
    switch($errno) {
        case VERBOSE_INDEXING:
        case VERBOSE_FORMAT_RENDERING:
        case VERBOSE_THEME_RENDERING:
        case VERBOSE_RENDER_STYLE:
        case VERBOSE_PARTIAL_READING:
        case VERBOSE_PARTIAL_CHILD_READING:
        case VERBOSE_TOC_WRITING:
        case VERBOSE_CHUNK_WRITING:
        case VERBOSE_MESSAGES:
            $color = Config::phd_info_color();
            $output = Config::phd_info_output();
            $data = $msg;
            break;

        case VERBOSE_NOVERSION:
        case VERBOSE_BROKEN_LINKS:
        case VERBOSE_MISSING_ATTRIBUTES:
            $color = Config::phd_warning_color();
            $output = Config::phd_warning_output();
            $data = $msg;
            break;

        // User triggered errors
        case E_USER_ERROR:
        case E_USER_WARNING:
        case E_USER_NOTICE:
            $color = Config::user_error_color();
            $output = Config::user_error_output();
            $data = sprintf("%s:%d\n\t%s", $file, $line, $msg);
            break;

        // PHP triggered errors
        case E_DEPRECATED:
        case E_RECOVERABLE_ERROR:
        case E_STRICT:
        case E_WARNING:
        case E_NOTICE:
            $color = Config::php_error_color();
            $output = Config::php_error_output();
            $data = sprintf("%s:%d\n\t%s", $file, $line, $msg);
            break;

        default:
            $recursive = false;
            return false;
    }

    $timestamp = term_color(sprintf("[%s - %s]", $time, $err[$errno]), $color);
    fprintf($output, "%s %s\n", $timestamp, $data);

    // Abort on fatal errors
    if ($errno & (E_USER_ERROR|E_RECOVERABLE_ERROR)) {
        exit(1);
    }

    $recursive = false;
    return true;
}
/* }}} */
set_error_handler(__NAMESPACE__ . '\\errh');
/* }}} */

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

