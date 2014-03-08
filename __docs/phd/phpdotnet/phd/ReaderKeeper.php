<?php
namespace phpdotnet\phd;
/* $Id$ */

class ReaderKeeper
{
    /**
     * Array of reader objects.
     *
     * @var array
     */
    protected static $r = array();

    /**
     * Appends a reader object to the stack.
     *
     * @param Reader $r Reader to append
     *
     * @return void
     */
    public static function setReader(Reader $r)
    {
        self::$r[] = $r;
    }

    /**
     * Returns the reader from the top of the stack.
     *
     * @return Reader Reader object
     */
    public static function getReader()
    {
        return end(self::$r);
    }

    /**
     * Removes the reader from the top of the stack.
     *
     * @return void
     */
    public static function popReader()
    {
        return array_pop(self::$r);
    }
}


/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

